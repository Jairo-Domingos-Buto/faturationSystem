document.addEventListener('DOMContentLoaded', function() {
    const apiUrl = '/api/categorias';
    const tabela = document.getElementById('tabela-categorias');
    const form = document.getElementById('categoria-form');
    let editandoId = null;

    async function carregarCategorias() {
        tabela.innerHTML =
            `<tr><td colspan="3" class="text-center text-muted">A carregar categorias...</td></tr>`;
        try {
            const res = await fetch(apiUrl);
            const data = await res.json();
            tabela.innerHTML = '';
            if (!data.length) {
                tabela.innerHTML =
                    `<tr><td colspan="3" class="text-center text-muted">Nenhuma categoria encontrada.</td></tr>`;
                return;
            }

            data.forEach(item => {
                tabela.innerHTML += `
                    <tr>
                        <td>${item.nome}</td>
                        <td>${item.descricao}</td>
                     <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);" onclick="editarCategoria(${item.id})">
                                            <i class="bx bx-edit-alt me-1"></i> Editar
                                        </a>
                                        <a class="dropdown-item" href="javascript:void(0);" onclick="excluirCategoria(${item.id})">
                                            <i class="bx bx-trash me-1"></i> Excluir
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>`;
            });
        } catch (err) {
            console.error(err);
            tabela.innerHTML =
                `<tr><td colspan="3" class="text-center text-danger">Erro ao carregar categorias.</td></tr>`;
        }
    }

    carregarCategorias();

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const dados = {
            nome: form.nome.value.trim(),
            descricao: form.descricao.value.trim(),
        };

        const url = editandoId ? `${apiUrl}/${editandoId}` : apiUrl;
        const method = editandoId ? 'PUT' : 'POST';

        try {
            const res = await fetch(url, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(dados)
            });

            if (!res.ok) throw new Error(await res.text());
            form.reset();
            editandoId = null;
            bootstrap.Modal.getInstance(document.getElementById('categoriaModal')).hide();
            carregarCategorias();
        } catch (err) {
            console.error('Erro ao salvar:', err);
            alert('Erro ao salvar categoria.');
        }
    });

    window.editarCategoria = async (id) => {
        const res = await fetch(`${apiUrl}/${id}`);
        const item = await res.json();
        form.nome.value = item.nome;
        form.descricao.value = item.descricao;
        editandoId = id;
        new bootstrap.Modal(document.getElementById('categoriaModal')).show();
    };

    window.excluirCategoria = async (id) => {
        if (!confirm('Desejas realmente excluir esta categoria?')) return;
        const res = await fetch(`${apiUrl}/${id}`, {
            method: 'DELETE'
        });
        if (res.ok) carregarCategorias();
    };
});