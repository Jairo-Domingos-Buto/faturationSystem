document.addEventListener('DOMContentLoaded', async function () {
    // URLs da sua API Laravel
    const urls = {
        clientes: '/api/clientes',
        fornecedores: '/api/fornecedores',
        produtos: '/api/produtos',
        servicos: '/api/servicos'
    };

    // Função genérica para buscar contagem
    async function contarRegistros(url) {
        try {
            const resposta = await fetch(url);
            if (!resposta.ok) throw new Error(`Erro ao acessar ${url}`);
            const dados = await resposta.json();

            // se o retorno for uma lista
            if (Array.isArray(dados.data)) return dados.data.length;
            if (Array.isArray(dados)) return dados.length;

            // se vier com meta pagination
            if (dados.meta && dados.meta.total) return dados.meta.total;

            return 0;
        } catch (erro) {
            console.error(erro);
            return 0;
        }
    }

    // Buscar e atualizar os cards
    const totalClientes = await contarRegistros(urls.clientes);
    const totalFornecedores = await contarRegistros(urls.fornecedores);
    const totalProdutos = await contarRegistros(urls.produtos);
    const totalServicos = await contarRegistros(urls.servicos);

    document.getElementById('total-clientes').textContent = totalClientes;
    document.getElementById('total-fornecedores').textContent = totalFornecedores;
    document.getElementById('total-produtos').textContent = totalProdutos;
    document.getElementById('total-servicos').textContent = totalServicos;
});
