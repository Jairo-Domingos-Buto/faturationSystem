document.addEventListener('DOMContentLoaded', function () {
    const apiUrl = '/api/impostos';
    const tabela = document.getElementById('tabela-impostos');
    const form = document.getElementById('imposto-form');
    let editandoId = null;

    function getCsrf() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.content : '';
    }
    // -----------------------
    // Carregar impostos (GET)
    // -----------------------
    async function carregarImpostos() {
        try {
            tabela.innerHTML = `<tr><td colspan="4" class="text-center text-muted">A carregar impostos...</td></tr>`;
            const res = await fetch(apiUrl, { headers: { 'Accept': 'application/json' } });
            if (!res.ok) throw new Error(`Erro ${res.status} ao listar impostos`);
            const data = await res.json();

            tabela.innerHTML = '';

            if (!Array.isArray(data) || data.length === 0) {
                tabela.innerHTML = `<tr><td colspan="4" class="text-center text-muted">Nenhum imposto cadastrado.</td></tr>`;
                return;
            }

            data.forEach(imposto => {
                const linha = `
                    <tr>
                        <td>${imposto.descricao ?? '-'}</td>
                        <td>${(imposto.taxa !== undefined && imposto.taxa !== null) ? imposto.taxa : '-'}</td>
                        <td>${imposto.codigo ?? '-'}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void(0);" onclick="editarImposto(${imposto.id})">
                                        <i class="bx bx-edit-alt me-1"></i> Editar
                                    </a>
                                    <a class="dropdown-item text-danger" href="javascript:void(0);" onclick="excluirImposto(${imposto.id})">
                                        <i class="bx bx-trash me-1"></i> Excluir
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                `;
                tabela.insertAdjacentHTML('beforeend', linha);
            });
        } catch (err) {
            console.error('carregarImpostos erro:', err);
            tabela.innerHTML = `<tr><td colspan="4" class="text-center text-danger">Erro ao carregar impostos!</td></tr>`;
        }
    }

    carregarImpostos();

    // -----------------------
    // Salvar / Editar imposto (POST / PUT)
    // -----------------------
    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const dados = {
            descricao: form.descricao.value.trim(),
            taxa: parseFloat(form.taxa.value),
            codigo: form.codigo.value.trim()
        };

        const method = editandoId ? 'PUT' : 'POST';
        const url = editandoId ? `${apiUrl}/${editandoId}` : apiUrl;

        try {
            const res = await fetch(url, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCsrf()
                },
                body: JSON.stringify(dados)
            });

            if (!res.ok) {
                const texto = await res.text();
                console.error('Erro ao salvar imposto, status:', res.status, 'res:', texto);
                alert('Erro ao salvar imposto. Ver console para mais detalhes.');
                return;
            }

            // fechar modal, reset e recarregar tabela
            $('#novoImpostoModal').modal('hide');
            form.reset();
            editandoId = null;
            carregarImpostos();
        } catch (err) {
            console.error('Salvar imposto erro:', err);
            alert('Erro ao salvar imposto. Ver console para mais detalhes.');
        }
    });

    // -----------------------
    // Editar imposto (GET /api/impostos/{id})
    // -----------------------
    window.editarImposto = async function (id) {
        try {
            const res = await fetch(`${apiUrl}/${id}`, { headers: { 'Accept': 'application/json' } });
            if (!res.ok) {
                console.error('Erro ao obter imposto:', res.status);
                alert('Erro ao carregar imposto. Ver console.');
                return;
            }
            const imposto = await res.json();

            // mapear campos explícitos
            form.descricao.value = imposto.descricao ?? '';
            form.taxa.value = imposto.taxa ?? '';
            form.codigo.value = imposto.codigo ?? '';

            editandoId = imposto.id ?? id;
            $('#novoImpostoModal').modal('show');
        } catch (err) {
            console.error('editarImposto erro:', err);
            alert('Erro ao carregar imposto. Ver console para detalhes.');
        }
    };

    // -----------------------
    // Excluir imposto (DELETE)
    // -----------------------
    window.excluirImposto = async function (id) {
        if (!confirm('Tens certeza que desejas excluir este imposto?')) return;

        try {
            const res = await fetch(`${apiUrl}/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCsrf()
                }
            });

            if (!res.ok) {
                const texto = await res.text();
                console.error('Erro ao excluir imposto, status:', res.status, 'res:', texto);
                alert('Erro ao excluir imposto. Ver console para detalhes.');
                return;
            }

            // sucesso
            carregarImpostos();
        } catch (err) {
            console.error('excluirImposto erro:', err);
            alert('Erro ao excluir imposto. Ver console para detalhes.');
        }
    };
});