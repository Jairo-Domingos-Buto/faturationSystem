document.addEventListener("DOMContentLoaded", function () {
    const apiProdutos = "/api/produtos";
    const apiCategorias = "/api/categorias";
    const apiFornecedores = "/api/fornecedores";
    const apiImpostos = "/api/impostos";
    const apiMotivos = '/api/motivo_isencaos';
    const impostoSelect = document.getElementById("imposto");
    const motivoContainer = document.getElementById("motivo-container");
    const motivoSelect = document.getElementById("motivo_isencao");

    const tabelaBody = document.getElementById("produtos-body");
    const categoriaSelect = document.getElementById("categoria");
    const fornecedorSelect = document.getElementById("fornecedor");
    const formProduto = document.getElementById("produto-form");
    const modalProduto = new bootstrap.Modal(
        document.getElementById("novoProdutoModal")
    );

    let editandoId = null; // 🔹 Controla se estamos editando

    // 🔹 Função CSRF
    function getCsrf() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.content : "";
    }
    // 🟢 Carregar Impostos
    function carregarImpostos() {
        fetch(apiImpostos)
            .then((res) => res.json())
            .then((data) => {
                // primeira opção fixa
                impostoSelect.innerHTML = `
                <option value="">-- Selecionar imposto --</option>
                <option value="nenhum">Nenhum</option>
            `;

                if (!data.length) {
                    impostoSelect.innerHTML +=
                        "<option disabled>Nenhum imposto encontrado</option>";
                } else {
                    data.forEach((imp) => {
                        impostoSelect.innerHTML += `<option value="${imp.id}">${imp.descricao} (${imp.taxa}%)</option>`;
                    });
                }
            })
            .catch((err) => console.error("Erro ao carregar impostos:", err));
    }

    // 🟢 Carregar Motivos de Isenção
    function carregarMotivos() {
        fetch(apiMotivos)
            .then((res) => res.json())
            .then((data) => {
                motivoSelect.innerHTML =
                    '<option value="">-- Selecionar motivo --</option>';
                if (!data.length) {
                    motivoSelect.innerHTML +=
                        "<option disabled>Nenhum motivo encontrado</option>";
                } else {
                    data.forEach((mot) => {
                        motivoSelect.innerHTML += `<option value="${mot.id}">${mot.codigo} - ${mot.razao}</option>`;
                    });
                }
            })
            .catch((err) => console.error("Erro ao carregar motivos:", err));
    }

    // 🟢 Carregar Categorias
    function carregarCategorias() {
        fetch(apiCategorias)
            .then((res) => res.json())
            .then((data) => {
                categoriaSelect.innerHTML =
                    '<option value="" disabled selected>Selecione a categoria</option>';
                if (!data.length) {
                    categoriaSelect.innerHTML +=
                        "<option disabled>Nenhuma categoria encontrada</option>";
                } else {
                    data.forEach((cat) => {
                        categoriaSelect.innerHTML += `<option value="${cat.id}">${cat.nome}</option>`;
                    });
                }
            })
            .catch((err) => console.error("Erro ao carregar categorias:", err));
    }

    // 🟢 Carregar Fornecedores
    function carregarFornecedores() {
        fetch(apiFornecedores)
            .then((res) => res.json())
            .then((data) => {
                fornecedorSelect.innerHTML =
                    '<option value="" disabled selected>Selecione o fornecedor</option>';
                if (!data.length) {
                    fornecedorSelect.innerHTML +=
                        "<option disabled>Nenhum fornecedor encontrado</option>";
                } else {
                    data.forEach((forn) => {
                        fornecedorSelect.innerHTML += `<option value="${forn.id}">${forn.nome}</option>`;
                    });
                }
            })
            .catch((err) =>
                console.error("Erro ao carregar fornecedores:", err)
            );
    }

    // 🟢 Carregar Produtos
    function carregarProdutos() {
        fetch(apiProdutos)
            .then((res) => res.json())
            .then((data) => {
                tabelaBody.innerHTML = "";
                if (!data.length) {
                    tabelaBody.innerHTML = `<tr><td colspan="7" class="text-center text-muted">Nenhum produto encontrado</td></tr>`;
                    return;
                }

                data.forEach((prod) => {
                    tabelaBody.innerHTML += `
                        <tr>
                            <td>${prod.descricao}</td>
                            <td>${prod.categoria?.nome || "—"}</td>
                            <td>${prod.fornecedor?.nome || "—"}</td>
                            <td>${prod.preco_compra} Kz</td>
                            <td>${prod.preco_venda} Kz</td>
                            <td>${prod.estoque}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item btn-editar" href="#" data-id="${
                                            prod.id
                                        }">
                                            <i class="bx bx-edit-alt me-1"></i> Editar
                                        </a>
                                        <a class="dropdown-item btn-excluir" href="#" data-id="${
                                            prod.id
                                        }">
                                            <i class="bx bx-trash me-1"></i> Excluir
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>`;
                });

                // 🟣 Adiciona eventos nos botões
                document.querySelectorAll(".btn-editar").forEach((btn) => {
                    btn.addEventListener("click", (e) =>
                        editarProduto(e.target.closest("a").dataset.id)
                    );
                });

                document.querySelectorAll(".btn-excluir").forEach((btn) => {
                    btn.addEventListener("click", (e) =>
                        excluirProduto(e.target.closest("a").dataset.id)
                    );
                });
            })
            .catch((err) => {
                console.error("Erro ao carregar produtos:", err);
                tabelaBody.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Erro ao carregar produtos</td></tr>`;
            });
    }

    document
        .getElementById("btn-imprimir-produtos")
        .addEventListener("click", function (e) {
            e.preventDefault();
            window.open("/admin/impressao/produtos", "_blank");
        });
    // 🟣 Editar Produto
    function editarProduto(id) {
        fetch(`${apiProdutos}/${id}`)
            .then((res) => {
                if (!res.ok) throw new Error("Produto não encontrado");
                return res.json();
            })
            .then((prod) => {
                editandoId = id;

                formProduto.querySelector("#descricao").value = prod.descricao;
                formProduto.querySelector("#preco_compra").value =
                    prod.preco_compra;
                formProduto.querySelector("#preco_venda").value =
                    prod.preco_venda;
                formProduto.querySelector("#estoque").value = prod.estoque;
                formProduto.querySelector("#categoria").value =
                    prod.categoria_id;
                formProduto.querySelector("#fornecedor").value =
                    prod.fornecedor_id;

                modalProduto.show();
            })
            .catch((err) => {
                console.error("Erro ao carregar produto:", err);
                alert("❌ Erro ao carregar dados do produto para edição.");
            });
    }

    // 🟣 Excluir Produto
    function excluirProduto(id) {
        if (!confirm("Deseja realmente excluir este produto?")) return;

        fetch(`${apiProdutos}/${id}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": getCsrf(),
                Accept: "application/json",
            },
        })
            .then((res) => {
                if (!res.ok) throw new Error("Erro ao excluir");
                return res.json();
            })
            .then(() => {
                alert("✅ Produto excluído com sucesso!");
                carregarProdutos();
            })
            .catch((err) => {
                console.error("Erro ao excluir produto:", err);
                alert("❌ Falha ao excluir o produto.");
            });
    }

 formProduto.addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(formProduto);

    // Capturar os selects
    const impostoVal = impostoSelect.value;
    const motivoVal = motivoSelect.value;

    // 👉 Se o imposto for "nenhum", tratamos como isento
    if (impostoVal === "nenhum" || impostoVal === "" || impostoVal === null) {
        formData.set("imposto_id", ""); // envia vazio (Laravel entende como null)

        if (!motivoVal) {
            alert("⚠️ Se o produto é isento, deve selecionar o motivo de isenção!");
            return;
        }

        formData.set("motivo_isencaos_id", motivoVal);
    } else {
        // 👉 Produto com imposto
        formData.set("imposto_id", impostoVal);
        formData.delete("motivo_isencaos_id");
    }

    const metodo = editandoId ? "POST" : "POST";
    const url = editandoId
        ? `${apiProdutos}/${editandoId}?_method=PUT`
        : apiProdutos;

    try {
        const res = await fetch(url, {
            method: metodo,
            headers: {
                "X-CSRF-TOKEN": getCsrf(),
                Accept: "application/json",
            },
            body: formData,
        });

        if (!res.ok) {
            const errorText = await res.text();
            console.error("Erro ao salvar produto:", errorText);
            alert("❌ Erro ao salvar produto. Veja o console.");
            return;
        }

        const data = await res.json();
        console.log("Produto salvo:", data);
        alert(editandoId ? "✅ Produto atualizado!" : "✅ Produto cadastrado!");
        formProduto.reset();
        modalProduto.hide();
        carregarProdutos();
        editandoId = null;
    } catch (err) {
        console.error("Erro ao salvar produto:", err);
        alert("❌ Erro ao salvar produto. Veja o console.");
    }
});


    // 🟣 Mostrar motivo de isenção apenas se o imposto for "nenhum"
    impostoSelect.addEventListener("change", function () {
        if (this.value === "nenhum") {
            motivoContainer.style.display = "block";
        } else {
            motivoContainer.style.display = "none";
            motivoSelect.value = "";
        }
    });

    // 🟢 Inicialização
    carregarCategorias();
    carregarFornecedores();
    carregarProdutos();
    carregarImpostos();
    carregarMotivos();
});
