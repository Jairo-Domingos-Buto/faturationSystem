document.addEventListener('DOMContentLoaded', function () {
    const apiUrl = '/api/fornecedores';
    const tabela = document.getElementById('tabela-fornecedores');
    const form = document.getElementById('fornecedor-form');
    let editandoId = null;

    // Utilitário para obter token CSRF (se existir)
    
    function getCsrf() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.content : '';
    }

    // ======================
    // LISTAR FORNECEDORES
    // ======================
    async function carregarFornecedores() {
        try {
            tabela.innerHTML = `<tr><td colspan="8" class="text-center text-muted">A carregar fornecedores...</td></tr>`;
            const res = await fetch(apiUrl, { headers: { 'Accept': 'application/json' }});
            if (!res.ok) throw new Error(`Erro ${res.status} ao listar fornecedores`);
            const data = await res.json();
            tabela.innerHTML = '';

            if (!Array.isArray(data) || data.length === 0) {
                tabela.innerHTML = `<tr><td colspan="8" class="text-center text-muted">Nenhum fornecedor encontrado.</td></tr>`;
                return;
            }

            data.forEach(fornecedor => {
                const linha = `
                    <tr>
                        <td><strong>${fornecedor.nome ?? '-'}</strong></td>
                        <td>${fornecedor.nif ?? '-'}</td>
                        <td>${fornecedor.email ?? '-'}</td>
                        <td>${fornecedor.telefone ?? '-'}</td>
                        <td>${fornecedor.provincia ?? '-'}</td>
                        <td>${fornecedor.cidade ?? '-'}</td>
                        <td>${fornecedor.localizacao ?? '-'}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void(0);" onclick="editarFornecedor(${fornecedor.id})">
                                        <i class="bx bx-edit-alt me-1"></i> Editar
                                    </a>
                                    <a class="dropdown-item text-danger" href="javascript:void(0);" onclick="excluirFornecedor(${fornecedor.id})">
                                        <i class="bx bx-trash me-1"></i> Excluir
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>`;
                tabela.insertAdjacentHTML('beforeend', linha);
            });
        } catch (err) {
            console.error('carregarFornecedores erro:', err);
            tabela.innerHTML = `<tr><td colspan="8" class="text-center text-danger">Erro ao carregar fornecedores!</td></tr>`;
        }
    }

    carregarFornecedores();

    // ======================
    // SALVAR / EDITAR FORNECEDOR (POST ou PUT)
    // ======================
    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const dados = {
            nome: form.nome.value.trim(),
            nif: form.nif.value.trim(),
            email: form.email.value.trim(),
            telefone: form.telefone.value.trim(),
            provincia: form.provincia.value.trim(),
            cidade: form.cidade.value.trim(),
            localizacao: form.localizacao.value.trim()
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
                console.error('Erro salvar fornecedor, status:', res.status, 'res:', texto);
                alert('Erro ao salvar fornecedor. Ver console para detalhes.');
                return;
            }

            // Se API devolver JSON com o recurso criado/atualizado, podes ler aqui:
            // const saved = await res.json();

            // fechar modal e recarregar tabela
            $('#novoFornecedorModal').modal('hide');
            form.reset();
            editandoId = null;
            carregarFornecedores();
        } catch (err) {
            console.error('Salvar fornecedor erro:', err);
            alert('Erro ao salvar fornecedor. Ver console para detalhes.');
        }
    });

    // ======================
    // EDITAR FORNECEDOR — obter dados e preencher modal
    // ======================
    // Exposto globalmente para onclick inline na tabela
    window.editarFornecedor = async function (id) {
        try {
            const res = await fetch(`${apiUrl}/${id}`, { headers: { 'Accept': 'application/json' }});
            if (!res.ok) {
                console.error('Erro ao obter fornecedor:', res.status);
                alert('Erro ao carregar fornecedor. Ver console.');
                return;
            }
            const fornecedor = await res.json();

            // Mapear explicitamente (evita problemas com nomes diferentes)
            form.nome.value = fornecedor.nome ?? '';
            form.nif.value = fornecedor.nif ?? '';
            form.email.value = fornecedor.email ?? '';
            form.telefone.value = fornecedor.telefone ?? '';
            form.provincia.value = fornecedor.provincia ?? '';
            form.cidade.value = fornecedor.cidade ?? '';
            form.localizacao.value = fornecedor.localizacao ?? '';

            editandoId = fornecedor.id ?? id;
            $('#novoFornecedorModal').modal('show');
        } catch (err) {
            console.error('editarFornecedor erro:', err);
            alert('Erro ao carregar fornecedor. Ver console para detalhes.');
        }
    };

    // ======================
    // EXCLUIR FORNECEDOR
    // ======================
    window.excluirFornecedor = async function (id) {
        if (!confirm('Tens certeza que desejas excluir este fornecedor?')) return;

        try {
            const res = await fetch(`${apiUrl}/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCsrf()
                }
            });

            // Algumas APIs devolvem 204 No Content, outras 200 com mensagem
            if (!res.ok) {
                const texto = await res.text();
                console.error('Erro ao excluir fornecedor, status:', res.status, 'res:', texto);
                alert('Erro ao excluir. Ver console para detalhes.');
                return;
            }

            // sucesso
            carregarFornecedores();
        } catch (err) {
            console.error('excluirFornecedor erro:', err);
            alert('Erro ao excluir fornecedor. Ver console para detalhes.');
        }
    };
});
