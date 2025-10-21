document.addEventListener("DOMContentLoaded", function () {
    const apiUrl = "/api/servicos";
    const tabela = document.getElementById("tabela-servicos");
    const form = document.getElementById("servico-form");
    const modal = document.getElementById("servicoModal");
    let editandoId = null;

    // ✅ Função para obter CSRF
    function getCsrf() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.content : "";
    }

    // 🔹 Carregar lista
    async function carregarServicos() {
        tabela.innerHTML = `<tr><td colspan="3" class="text-center text-muted">A carregar serviços...</td></tr>`;
        try {
            const res = await fetch(apiUrl);
            const data = await res.json();
            tabela.innerHTML = "";
            if (!data.length) {
                tabela.innerHTML = `<tr><td colspan="3" class="text-center text-muted">Nenhum serviço encontrado.</td></tr>`;
                return;
            }
            data.forEach((item) => {
                tabela.innerHTML += `
                    <tr>
                        <td>${item.descricao}</td>
                        <td>${parseFloat(item.valor).toLocaleString("pt-AO", {
                            style: "currency",
                            currency: "AOA",
                        })}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void(0);" onclick="editarServico(${
                                        item.id
                                    })">
                                        <i class="bx bx-edit-alt me-1"></i> Editar
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0);" onclick="excluirServico(${
                                        item.id
                                    })">
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

    carregarServicos();

    // 🔹 Salvar / Atualizar
    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const dados = {
            descricao: form.descricao.value.trim(),
            valor: form.valor.value.trim(),
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
                const text = await res.text();
                console.error("Erro ao salvar serviço:", text);
                alert("❌ Erro ao salvar serviço. Veja o console.");
                return;
            }

            form.reset();
            editandoId = null;
            bootstrap.Modal.getInstance(modal).hide();
            carregarServicos();
        } catch (err) {
            console.error("Erro ao salvar serviço:", err);
            alert("❌ Erro ao salvar serviço.");
        }
    });

    document
        .getElementById("btn-imprimir")
        .addEventListener("click", function () {
            // Quando o botão for clicado, abre a rota do PDF em nova aba
            window.open("/admin/impressao/servicos", "_blank");
        });

    // 🔹 Editar
    window.editarServico = async (id) => {
        try {
            const res = await fetch(`${apiUrl}/${id}`);
            const item = await res.json();
            form.descricao.value = item.descricao;
            form.valor.value = item.valor;
            editandoId = id;
            new bootstrap.Modal(modal).show();
        } catch (err) {
            console.error("Erro ao carregar serviço para edição:", err);
        }
    };

    // 🔹 Excluir
    window.excluirServico = async (id) => {
        if (!confirm("Desejas realmente excluir este serviço?")) return;
        try {
            const res = await fetch(`${apiUrl}/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": getCsrf(),
                },
            });
            if (res.ok) carregarServicos();
            else alert("Erro ao excluir serviço.");
        } catch (err) {
            console.error("Erro ao excluir serviço:", err);
        }
    };
});
