document.addEventListener("DOMContentLoaded", function () {
    const apiUrl = "/api/servicos";
    const tabela = document.getElementById("tabela-servicos");
    const form = document.getElementById("servico-form");
    const modalElement = document.getElementById("servicoModal");
    // Inicializa o modal via JS para evitar conflitos
    const modalInstance = new bootstrap.Modal(modalElement);
    let editandoId = null;

    // ✅ Função para obter CSRF do Laravel
    function getCsrf() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.content : "";
    }

    // ✅ RESET DO FORMULÁRIO
    // Isso garante que ao fechar o modal (por salvar, cancelar ou clicar fora), tudo seja limpo
    modalElement.addEventListener("hidden.bs.modal", function () {
        form.reset();
        editandoId = null;
        document.querySelector(".modal-title").innerHTML =
            '<i class="bx bx-cog me-2"></i> Cadastro de Serviço';
    });

    // 🔹 Carregar lista de serviços
    async function carregarServicos() {
        tabela.innerHTML = `<tr><td colspan="3" class="text-center text-muted">A carregar serviços...</td></tr>`;
        try {
            const res = await fetch(apiUrl);
            const data = await res.json();
            tabela.innerHTML = "";

            if (!data || !data.length) {
                tabela.innerHTML = `<tr><td colspan="3" class="text-center text-muted">Nenhum serviço encontrado.</td></tr>`;
                return;
            }

            data.forEach((item) => {
                // Formatação correta para Kwanzas
                const valorFormatado = parseFloat(item.valor).toLocaleString(
                    "pt-AO",
                    {
                        style: "currency",
                        currency: "AOA",
                    }
                );

                tabela.innerHTML += `
                    <tr>
                        <td>${item.descricao}</td>
                        <td class="text-end">${valorFormatado}</td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void(0);" onclick="editarServico(${item.id})">
                                        <i class="bx bx-edit-alt me-1"></i> Editar
                                    </a>
                                    <a class="dropdown-item text-danger" href="javascript:void(0);" onclick="excluirServico(${item.id})">
                                        <i class="bx bx-trash me-1"></i> Excluir
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>`;
            });
        } catch (err) {
            console.error("Erro ao carregar serviços:", err);
            tabela.innerHTML = `<tr><td colspan="3" class="text-center text-danger">Erro ao carregar serviços.</td></tr>`;
        }
    }

    // Carrega inicialmente
    carregarServicos();

    // 🔹 Salvar / Atualizar
    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const dados = {
            descricao: form.descricao.value.trim(),
            valor: form.valor.value, // input type number já trata isso, mas cuidado se vir vazio
        };

        const url = editandoId ? `${apiUrl}/${editandoId}` : apiUrl;
        const method = editandoId ? "PUT" : "POST";

        try {
            const res = await fetch(url, {
                method,
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": getCsrf(),
                },
                body: JSON.stringify(dados),
            });

            if (!res.ok) {
                // Tenta ler o erro do backend se existir
                const errorData = await res.json().catch(() => ({}));
                console.error("Erro API:", errorData);
                alert(
                    "❌ Erro ao salvar: " +
                        (errorData.message || "Verifique os dados.")
                );
                return;
            }

            // Sucesso
            modalInstance.hide(); // O evento hidden.bs.modal vai rodar e limpar o form
            carregarServicos();
        } catch (err) {
            console.error("Erro na requisição:", err);
            alert("❌ Erro de conexão ao salvar serviço.");
        }
    });

    // 🔹 Botão Imprimir
    const btnImprimir = document.getElementById("btn-imprimir");
    if (btnImprimir) {
        btnImprimir.addEventListener("click", function () {
            window.open("/admin/impressao/servicos", "_blank");
        });
    }

    // 🔹 Editar (Exposto globalmente para o onclick do HTML funcionar)
    window.editarServico = async (id) => {
        try {
            const res = await fetch(`${apiUrl}/${id}`);
            if (!res.ok) throw new Error("Erro ao buscar serviço");

            const item = await res.json();

            form.descricao.value = item.descricao;
            form.valor.value = item.valor;
            editandoId = id;

            // Muda titulo visualmente
            document.querySelector(".modal-title").innerHTML =
                '<i class="bx bx-edit me-2"></i> Editar Serviço';

            modalInstance.show();
        } catch (err) {
            console.error("Erro ao carregar serviço para edição:", err);
            alert("Erro ao carregar dados do serviço.");
        }
    };

    // 🔹 Excluir (Exposto globalmente)
    window.excluirServico = async (id) => {
        if (!confirm("Tem a certeza que deseja excluir este serviço?")) return;

        try {
            const res = await fetch(`${apiUrl}/${id}`, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": getCsrf(),
                },
            });

            if (res.ok) {
                carregarServicos();
            } else {
                alert(
                    "Não foi possível excluir o serviço. Verifique se ele já está em uso em alguma fatura."
                );
            }
        } catch (err) {
            console.error("Erro ao excluir serviço:", err);
        }
    };
});
