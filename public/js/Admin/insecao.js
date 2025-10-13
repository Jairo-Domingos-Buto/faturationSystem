document.addEventListener('DOMContentLoaded', function () {
    const apiUrl = '/api/motivo_isencaos';
    const tabela = document.getElementById('tabela-isencoes');
    const form = document.getElementById('isencao-form');
    let editandoId = null;

    function getCsrf() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.content : '';
    }

    // -----------------------
    // LISTAR ISENÇÕES
    // -----------------------
    async function carregarIsencoes() {
        try {
            tabela.innerHTML = `<tr><td colspan="4" class="text-center text-muted">A carregar motivos de isenção...</td></tr>`;
            const res = await fetch(apiUrl, { headers: { 'Accept': 'application/json' } });
            if (!res.ok) throw new Error(`Erro ${res.status}`);
            const data = await res.json();

            tabela.innerHTML = '';
            if (!data.length) {
                tabela.innerHTML = `<tr><td colspan="4" class="text-center text-muted">Nenhum motivo de isenção encontrado.</td></tr>`;
                return;
            }

            data.forEach(item => {
                tabela.insertAdjacentHTML('beforeend', `
                    <tr>
                        <td>${item.codigo}</td>
                        <td>${item.razao}</td>
                        <td>${item.descricao}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn p-0 dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="editarIsencao(${item.id})">
                                        <i class="bx bx-edit-alt me-1"></i> Editar
                                    </a>
                                    <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="excluirIsencao(${item.id})">
                                        <i class="bx bx-trash me-1"></i> Excluir
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                `);
            });
        } catch (err) {
            console.error('Erro ao carregar:', err);
            tabela.innerHTML = `<tr><td colspan="4" class="text-center text-danger">Erro ao carregar isenções.</td></tr>`;
        }
    }

    carregarIsencoes();

    // -----------------------
    // SALVAR / EDITAR
    // -----------------------
    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const dados = {
            codigo: form.codigo.value.trim(),
            razao: form.razao.value.trim(),
            descricao: form.descricao.value.trim(),
        };

        const method = editandoId ? 'PUT' : 'POST';
        const url = editandoId ? `${apiUrl}/${editandoId}` : apiUrl;

        try {
            const res = await fetch(url, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCsrf(),
                },
                body: JSON.stringify(dados)
            });

            if (!res.ok) {
                const errText = await res.text();
                console.error('Erro ao salvar:', errText);
                alert('Erro ao salvar isenção. Verifique os dados.');
                return;
            }

            form.reset();
            editandoId = null;
            bootstrap.Modal.getInstance(document.getElementById('isencaoModal')).hide();
            carregarIsencoes();
        } catch (err) {
            console.error('Salvar erro:', err);
            alert('Erro ao salvar isenção.');
        }
    });

    // -----------------------
    // EDITAR
    // -----------------------
    window.editarIsencao = async function (id) {
        try {
            const res = await fetch(`${apiUrl}/${id}`, { headers: { 'Accept': 'application/json' } });
            if (!res.ok) throw new Error('Erro ao buscar isenção');
            const item = await res.json();

            form.codigo.value = item.codigo;
            form.razao.value = item.razao;
            form.descricao.value = item.descricao;

            editandoId = id;
            new bootstrap.Modal(document.getElementById('isencaoModal')).show();
        } catch (err) {
            console.error('Editar erro:', err);
            alert('Erro ao carregar dados.');
        }
    };

    // -----------------------
    // EXCLUIR
    // -----------------------
    window.excluirIsencao = async function (id) {
        if (!confirm('Tens certeza que desejas excluir este motivo de isenção?')) return;

        try {
            const res = await fetch(`${apiUrl}/${id}`, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': getCsrf() }
            });

            if (!res.ok) throw new Error('Erro ao excluir');
            carregarIsencoes();
        } catch (err) {
            console.error('Excluir erro:', err);
            alert('Erro ao excluir isenção.');
        }
    };
});
