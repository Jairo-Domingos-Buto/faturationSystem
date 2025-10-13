document.addEventListener('DOMContentLoaded', function() {
    const apiProdutos = '/api/produtos';
    const apiCategorias = '/api/categorias';
    const apiFornecedores = '/api/fornecedores';

    const tabelaBody = document.getElementById('produtos-body');
    const categoriaSelect = document.getElementById('categoria');
    const fornecedorSelect = document.getElementById('fornecedor');
    const formProduto = document.getElementById('produto-form');

    // 🟢 Carregar Categorias
    fetch(apiCategorias)
        .then(res => res.json())
        .then(data => {
            categoriaSelect.innerHTML = '<option value="" disabled selected>Selecione a categoria</option>';
            if (data.length === 0) {
                categoriaSelect.innerHTML += '<option disabled>Nenhuma categoria encontrada</option>';
            } else {
                data.forEach(cat => {
                    categoriaSelect.innerHTML += `<option value="${cat.id}">${cat.nome}</option>`;
                });
            }
        });

    // 🟢 Carregar Fornecedores
    fetch(apiFornecedores)
        .then(res => res.json())
        .then(data => {
            fornecedorSelect.innerHTML =
                '<option value="" disabled selected>Selecione o fornecedor</option>';
            if (data.length === 0) {
                fornecedorSelect.innerHTML += '<option disabled>Nenhum fornecedor encontrado</option>';
            } else {
                data.forEach(forn => {
                    fornecedorSelect.innerHTML +=
                    `<option value="${forn.id}">${forn.nome}</option>`;
                });
            }
        });

    // 🟢 Carregar Produtos
    function carregarProdutos() {
        fetch(apiProdutos)
            .then(res => res.json())
            .then(data => {
                tabelaBody.innerHTML = '';
                if (data.length === 0) {
                    tabelaBody.innerHTML =
                        `<tr><td colspan="7" class="text-center text-muted">Nenhum produto encontrado</td></tr>`;
                } else {
                    data.forEach(prod => {
                        tabelaBody.innerHTML += `
                            <tr>
                                <td>${prod.nome}</td>
                                <td>${prod.categoria?.nome || '—'}</td>
                                <td>${prod.fornecedor?.nome || '—'}</td>
                                <td>${prod.preco_compra} Kz</td>
                                <td>${prod.preco_venda} Kz</td>
                                <td>${prod.estoque}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#"><i class="bx bx-edit-alt me-1"></i> Editar</a>
                                            <a class="dropdown-item" href="#"><i class="bx bx-trash me-1"></i> Excluir</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>`;
                    });
                }
            })
            .catch(() => {
                tabelaBody.innerHTML =
                    `<tr><td colspan="7" class="text-center text-danger">Erro ao carregar produtos</td></tr>`;
            });
    }

    carregarProdutos();

    // 🟢 Submeter Formulário
    formProduto.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(formProduto);

        fetch(apiProdutos, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => {
                if (!res.ok) throw new Error('Erro ao cadastrar produto');
                return res.json();
            })
            .then(() => {
                alert('Produto cadastrado com sucesso!');
                formProduto.reset();
                carregarProdutos();
                const modal = bootstrap.Modal.getInstance(document.getElementById(
                    'novoProdutoModal'));
                modal.hide();
            })
            .catch(err => {
                console.error(err);
                alert('Erro ao salvar produto. Verifique o console.');
            });
    });
});