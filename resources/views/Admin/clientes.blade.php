@extends('layout.main')

@section('content')

<div class="min-h-screen bg-slate-50 p-6">

    {{-- Cabeçalho --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                <i class='bx bx-group text-indigo-600'></i> Gestão de Clientes
            </h1>
            <p class="text-sm text-slate-500 mt-1">Consulte e adicione novos clientes ao sistema.</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            {{-- Barra de Pesquisa --}}
            <div class="relative">
                <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg'></i>
                <input type="text" id="search-cliente"
                    class="pl-10 pr-4 py-2.5 w-full sm:w-64 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                    placeholder="Pesquisar por nome ou NIF...">
            </div>

            {{-- Botão Novo --}}
            <button type="button" data-bs-toggle="modal" data-bs-target="#novoClienteModal"
                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-md transition-all flex items-center justify-center gap-2">
                <i class='bx bx-user-plus text-xl'></i> Novo Cliente
            </button>
        </div>
    </div>

    {{-- Tabela de Clientes --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap text-left text-sm" id="clientes-table">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-xs border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Nome</th>
                        <th class="px-6 py-4">NIF</th>
                        <th class="px-6 py-4">Província</th>
                        <th class="px-6 py-4">Cidade</th>
                        <th class="px-6 py-4">Telefone</th>
                        <th class="px-6 py-4 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    {{--
                    O JavaScript (clientes.js) deve preencher aqui.
                    Este é um estado de carregamento inicial estilizado.
                    --}}
                    <tr id="loading-row">
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center">
                                <i class='bx bx-loader-alt animate-spin text-3xl mb-3 text-indigo-500'></i>
                                <p>A carregar clientes...</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Novo Cliente (Estilizado mas compatível com Bootstrap JS) -->
<div class="modal fade" id="novoClienteModal" tabindex="-1" aria-labelledby="novoClienteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-2xl shadow-2xl overflow-hidden">

            <form id="cliente-form" enctype="multipart/form-data" method="POST">
                @csrf

                <!-- Modal Header -->
                <div
                    class="modal-header bg-slate-900 px-6 py-4 border-b border-slate-800 flex justify-between items-center">
                    <h5 class="text-white font-bold text-lg flex items-center gap-2" id="novoClienteModalLabel">
                        <i class="bx bx-user-plus text-indigo-400"></i> Novo Cliente
                    </h5>
                    <!-- Botão de fechar customizado para combinar -->
                    <button type="button" class="text-slate-400 hover:text-white transition-colors"
                        data-bs-dismiss="modal" aria-label="Fechar">
                        <i class='bx bx-x text-2xl'></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body bg-white p-6 md:p-8 space-y-5">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Nome -->
                        <div class="col-span-1 md:col-span-2">
                            <label for="nome" class="block text-sm font-semibold text-slate-600 mb-1.5">Nome
                                Completo</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400"><i
                                        class="bx bx-user"></i></span>
                                <input type="text"
                                    class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-lg text-slate-700 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all"
                                    id="nome" name="nome" placeholder="Ex: João Manuel" required>
                            </div>
                        </div>

                        <!-- NIF -->
                        <div>
                            <label for="nif" class="block text-sm font-semibold text-slate-600 mb-1.5">NIF</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400"><i
                                        class="bx bx-id-card"></i></span>
                                <input type="text"
                                    class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-lg text-slate-700 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all"
                                    id="nif" name="nif" placeholder="Ex: 005678910LA043">
                            </div>
                        </div>

                        <!-- Telefone -->
                        <div>
                            <label for="telefone"
                                class="block text-sm font-semibold text-slate-600 mb-1.5">Telefone</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400"><i
                                        class="bx bx-phone"></i></span>
                                <input type="text"
                                    class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-lg text-slate-700 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all"
                                    id="telefone" name="telefone" placeholder="Ex: 944 273 243">
                            </div>
                        </div>

                        <!-- Província -->
                        <div>
                            <label for="provincia"
                                class="block text-sm font-semibold text-slate-600 mb-1.5">Província</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400"><i
                                        class="bx bx-map-alt"></i></span>
                                <input type="text"
                                    class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-lg text-slate-700 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all"
                                    id="provincia" name="provincia" placeholder="Ex: Luanda">
                            </div>
                        </div>

                        <!-- Cidade -->
                        <div>
                            <label for="cidade" class="block text-sm font-semibold text-slate-600 mb-1.5">Cidade</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400"><i
                                        class="bx bx-buildings"></i></span>
                                <input type="text"
                                    class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-lg text-slate-700 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all"
                                    id="cidade" name="cidade" placeholder="Ex: Kilamba">
                            </div>
                        </div>

                        <!-- Localização (Full width) -->
                        <div class="col-span-1 md:col-span-2">
                            <label for="localizacao" class="block text-sm font-semibold text-slate-600 mb-1.5">Endereço
                                / Localização</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400"><i
                                        class="bx bx-map-pin"></i></span>
                                <input type="text"
                                    class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-lg text-slate-700 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all"
                                    id="localizacao" name="localizacao" placeholder="Ex: Rua do Nova Vida, nº 25">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer bg-slate-50 px-6 py-4 flex justify-end gap-2 border-t border-slate-200">
                    <button type="button"
                        class="px-4 py-2 text-slate-600 bg-white border border-slate-300 hover:bg-slate-50 hover:text-slate-800 rounded-lg transition-colors font-medium"
                        data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-md transition-colors font-medium flex items-center gap-2">
                        <i class="bx bx-save"></i> Guardar Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Scripts (Mantendo seu arquivo original) --}}
<script src="{{ asset('js/Admin/clientes.js') }}"></script>

@endsection