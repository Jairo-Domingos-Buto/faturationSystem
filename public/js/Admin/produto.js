document.addEventListener('DOMContentLoaded', function() {
    const apiProdutos = '/api/produtos';
    const apiCategorias = '/api/categorias';
    const apiFornecedores = '/api/fornecedores';

    const tabelaBody = document.getElementById('produtos-body');
    const categoriaSelect = document.getElementById('categoria');
    const fornecedorSelect = document.getElementById('fornecedor');
    const formProduto = document.getElementById('produto-form');

    // 🔹 Função CSRF
    function getCsrf() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.content : '';
    }

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
        })
        .catch(err => console.error('Erro ao carregar categorias:', err));

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
        })
        .catch(err => console.error('Erro ao carregar fornecedores:', err));

    // 🟢 Carregar Produtos
    function carregarProdutos() {
        fetch(apiProdutos)
            .then(res => res.json())
            .then(data => {
                tabelaBody.innerHTML = '';
                if (!data.length) {
                    tabelaBody.innerHTML =
                        `<tr><td colspan="7" class="text-center text-muted">Nenhum produto encontrado</td></tr>`;
                    return;
                }

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
            })
            .catch(err => {
                console.error('Erro ao carregar produtos:', err);
                tabelaBody.innerHTML =
                    `<tr><td colspan="7" class="text-center text-danger">Erro ao carregar produtos</td></tr>`;
            });
    }

    carregarProdutos();

    // 🟢 Submeter Formulário
    formProduto.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(formProduto);

        try {
            console.log('Enviando produto...');
            const res = await fetch(apiProdutos, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': getCsrf()
                },
                body: formData
            });

            if (!res.ok) {
                const errorText = await res.text();
                console.error('Erro ao cadastrar produto:', errorText);
                throw new Error('Falha ao cadastrar produto');
            }

            const data = await res.json();
            console.log('Produto cadastrado:', data);

            alert('✅ Produto cadastrado com sucesso!');
            formProduto.reset();
            carregarProdutos();

            const modal = bootstrap.Modal.getInstance(document.getElementById('novoProdutoModal'));
            modal.hide();
        } catch (err) {
            console.error('Erro ao salvar produto:', err);
            alert('❌ Erro ao salvar produto. Verifique o console.');
        }
    });
});
