<div class="bg-white p-6 min-h-screen">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Exportar SAFT (AGT)</h2>

    <div class="bg-gray-50 rounded-lg border p-4 mb-6">

        <!-- Formulário que faz POST direto para a rota de download -->
        <form action="/api/saft/export" method="POST" target="_blank">
            @csrf <!-- Importante para segurança -->

            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-2">
                    <label class="font-bold text-gray-700">De:</label>
                    <input type="date" name="start_date" required
                        value="{{ now()->startOfMonth()->format('Y-m-d') }}"
                        class="border-gray-300 p-2 rounded shadow-sm focus:ring-blue-500">

                    <label class="font-bold text-gray-700">Até:</label>
                    <input type="date" name="end_date" required
                        value="{{ now()->format('Y-m-d') }}"
                        class="border-gray-300 p-2 rounded shadow-sm focus:ring-blue-500">
                </div>

                <button type="submit"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded shadow-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Baixar Ficheiro XML
                </button>
            </div>
        </form>

    </div>

    <div class="bg-yellow-50 p-4 border-l-4 border-yellow-400">
        <p class="text-sm text-yellow-700">
            <strong>Nota Técnica:</strong> O ficheiro será gerado com os documentos faturados e assinados digitalmente.
            Certifique-se de que não existem erros na Tabela de Clientes (NIF) antes de enviar para a AGT.
        </p>
    </div>
</div>
