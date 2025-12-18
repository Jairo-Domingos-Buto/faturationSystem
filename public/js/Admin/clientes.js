document.addEventListener('DOMContentLoaded', function () {
    const apiUrl = '/api/clientes';
    const tabela = document.querySelector('tbody');
    const form = document.getElementById('cliente-form');
    let editandoId = null;

    // 🔹 LISTAR CLIENTES
    function carregarClientes() {
        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                tabela.innerHTML = '';
                if (!data.length) {
                    tabela.innerHTML = `<tr><td colspan="6" class="text-center text-muted">Nenhum cliente encontrado.</td></tr>`;
                    return;
                }
                data.forEach(cliente => {
                    tabela.insertAdjacentHTML('beforeend', `
                        <tr>
                            <td><strong>${cliente.nome}</strong></td>
                            <td>${cliente.nif ?? ''}</td>
                            <td>${cliente.provincia ?? ''}</td>
                            <td>${cliente.cidade ?? ''}</td>
                            <td>${cliente.telefone ?? ''}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);" onclick="editarCliente(${cliente.id})">
                                            <i class="bx bx-edit-alt me-1"></i> Editar
                                        </a>
                                        <a class="dropdown-item" href="javascript:void(0);" onclick="excluirCliente(${cliente.id})">
                                            <i class="bx bx-trash me-1"></i> Excluir
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `);
                });
            })
            .catch(error => {
                console.error('Erro ao carregar clientes:', error);
                tabela.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Erro ao carregar dados.</td></tr>`;
            });
    }

    carregarClientes();

    // 🔹 SALVAR CLIENTE
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const dados = {
            nome: nome.value,
            nif: nif.value,
            provincia: provincia.value,
            cidade: cidade.value,
            localizacao: localizacao.value,
            telefone: telefone.value
        };

        const method = editandoId ? 'PUT' : 'POST';
        const url = editandoId ? `${apiUrl}/${editandoId}` : apiUrl;

        fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(dados)
        })
        .then(res => res.json())
        .then(() => {
            $('#novoClienteModal').modal('hide');
            form.reset();
            editandoId = null;
            carregarClientes();
        })
        .catch(error => console.error('Erro ao guardar cliente:', error));
    });

    // 🔹 EDITAR CLIENTE
    window.editarCliente = function (id) {
        fetch(`${apiUrl}/${id}`)
            .then(res => res.json())
            .then(cliente => {
                editandoId = id;
                nome.value = cliente.nome;
                nif.value = cliente.nif;
                provincia.value = cliente.provincia;
                cidade.value = cliente.cidade;
                localizacao.value = cliente.localizacao;
                telefone.value = cliente.telefone;
                $('#novoClienteModal').modal('show');
            })
            .catch(error => console.error('Erro ao carregar cliente:', error));
    };

    // 🔹 EXCLUIR CLIENTE
    window.excluirCliente = function (id) {
        if (confirm('Tens certeza que desejas excluir este cliente?')) {
            fetch(`${apiUrl}/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(() => carregarClientes())
            .catch(error => console.error('Erro ao excluir cliente:', error));
        }
    };
});
