@extends('layout.main')

@section('content')
    <div class="max-w-7xl mx-auto p-6">
      <div class="bg-white-200 rounded-lg p-6 shadow-sm">
        <!-- Logo and Name Section -->
        <div class="flex justify-between items-start mb-6">
          <div class="flex-1">
            <label class="block text-gray-700 text-sm font-medium mb-2">Nome</label>
            <input type="text" value="Minha Empresa, Lda"
              class="w-full bg-white-100 border border-white-300 rounded px-4 py-2 text-gray-800 focus:outline-none focus:border-white-400">
          </div>
          <div class="ml-6 flex flex-col items-center gap-3">
            <div class="bg-white rounded p-4 w-32 h-32 flex items-center justify-center border border-white-300">
              <span class="text-gray-400">Logo</span>
            </div>
            <button class="bg-gray-700 text-white px-6 py-2 rounded hover:bg-gray-800">Alterar</button>
          </div>
        </div>

        <!-- NIF -->
        <div class="mb-6">
          <label class="block text-gray-700 text-sm font-medium mb-2">NIF</label>
          <input type="text" value="123456789"
            class="bg-white-100 border border-white-300 rounded px-4 py-2 text-gray-800 w-64 focus:outline-none focus:border-white-400">
        </div>

        <!-- Contact Information Row -->
        <div class="grid grid-cols-3 gap-4 mb-6">
          <div>
            <label class="block text-gray-700 text-sm font-medium mb-2">Telefone</label>
            <input type="text" value="123456789"
              class="w-full bg-white-100 border border-white-300 rounded px-4 py-2 text-gray-800 focus:outline-none focus:border-white-400">
          </div>
          <div>
            <label class="block text-gray-700 text-sm font-medium mb-2">Email</label>
            <input type="email" value="minhaempresa@gmail.com"
              class="w-full bg-white-100 border border-white-300 rounded px-4 py-2 text-gray-800 focus:outline-none focus:border-white-400">
          </div>
          <div>
            <label class="block text-gray-700 text-sm font-medium mb-2">Website</label>
            <input type="text" value="www.minhaempresa.com"
              class="w-full bg-white-100 border border-white-300 rounded px-4 py-2 text-gray-800 focus:outline-none focus:border-white-400">
          </div>
        </div>

        <!-- Bank and IBAN Row -->
        <div class="grid grid-cols-2 gap-4 mb-6">
          <div>
            <label class="block text-gray-700 text-sm font-medium mb-2">Nome do Banco</label>
            <input type="text" value="Banco X"
              class="w-full bg-white-100 border border-white-300 rounded px-4 py-2 text-gray-800 focus:outline-none focus:border-white-400">
          </div>
          <div>
            <label class="block text-gray-700 text-sm font-medium mb-2">IBAN</label>
            <input type="text" value="AO123456789"
              class="w-full bg-white-100 border border-white-300 rounded px-4 py-2 text-gray-800 focus:outline-none focus:border-white-400">
          </div>
        </div>

        <!-- Address Row -->
        <div class="grid grid-cols-3 gap-4 mb-6">
          <div>
            <label class="block text-gray-700 text-sm font-medium mb-2">Cidade</label>
            <input type="text" value="Luanda"
              class="w-full bg-white-100 border border-white-300 rounded px-4 py-2 text-gray-800 focus:outline-none focus:border-white-400">
          </div>
          <div>
            <label class="block text-gray-700 text-sm font-medium mb-2">Rua</label>
            <input type="text" value="Rua Principal"
              class="w-full bg-white-100 border border-white-300 rounded px-4 py-2 text-gray-800 focus:outline-none focus:border-white-400">
          </div>
          <div>
            <label class="block text-gray-700 text-sm font-medium mb-2">Edifício</label>
            <input type="text" value="123"
              class="w-full bg-white-100 border border-white-300 rounded px-4 py-2 text-gray-800 focus:outline-none focus:border-white-400">
          </div>
        </div>

        <!-- Location -->
        <div class="mb-6">
          <label class="block text-gray-700 text-sm font-medium mb-2">Localização</label>
          <input type="text" value="Viana"
            class="bg-white-100 border border-white-300 rounded px-4 py-2 text-gray-800 w-64 focus:outline-none focus:border-white-400">
        </div>

        <!-- Region -->
        <div class="mb-6">
          <label class="block text-gray-700 text-sm font-medium mb-2">Regime</label>
          <input type="text" value="Nenhum"
            class="bg-white-100 border border-white-300 rounded px-4 py-2 text-gray-800 w-64 focus:outline-none focus:border-white-400">
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3">
          <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-medium flex items-center gap-2">
            Salvar Informações
          </button>
          <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path>
            </svg>
            Opções de Impressão
          </button>
        </div>
      </div>
    </div>
@endsection
