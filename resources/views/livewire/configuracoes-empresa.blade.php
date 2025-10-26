<div class="max-w-7xl mx-auto p-6">
    <form wire:submit="save" class="bg-white-200 rounded-lg p-6 shadow-sm">
        <!-- Logo and Name Section -->
        <div class="flex justify-between items-start mb-6">
            <div class="flex-1">
                <label class="block text-gray-700 text-sm font-medium mb-2">Nome</label>
                <input type="text" wire:model="name"
                    class="w-full bg-white-100 border border-white-300 rounded px-4 py-2 text-gray-800 focus:outline-none focus:border-white-400">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="ml-6 flex flex-col items-center gap-3">
                <div class="bg-white rounded p-4 w-32 h-32 flex items-center justify-center border border-white-300">
                    <span class="text-gray-400">Logo</span>
                </div>
                <button type="button"
                    class="bg-gray-700 text-white px-6 py-2 rounded hover:bg-gray-800">Alterar</button>
            </div>
        </div>

        <!-- NIF -->
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-medium mb-2">NIF</label>
            <input type="text" wire:model="nif"
                class="bg-white-100 border border-white-300 rounded px-4 py-2 text-gray-800 w-64 focus:outline-none focus:border-white-400">
            @error('nif') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Contact Information Row -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-2">Telefone</label>
                <input type="text" wire:model="telefone"
                    class="w-full bg-white-100 border border-white-300 rounded px-4 py-2 text-gray-800 focus:outline-none focus:border-white-400">
                @error('telefone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-2">Email</label>
                <input type="email" wire:model="email"
                    class="w-full bg-white-100 border border-white-300 rounded px-4 py-2 text-gray-800 focus:outline-none focus:border-white-400">
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-2">Website</label>
                <input type="text" wire:model="website"
                    class="w-full bg-white-100 border border-white-300 rounded px-4 py-2 text-gray-800 focus:outline-none focus:border-white-400">
                @error('website') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Bank and IBAN Row -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-2">Nome do Banco</label>
                <input type="text" wire:model="banco"
                    class="w-full bg-white-100 border border-white-300 rounded px-4 py-2 text-gray-800 focus:outline-none focus:border-white-400">
                @error('banco') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-2">IBAN</label>
                <input type="text" wire:model="iban"
                    class="w-full bg-white-100 border border-white-300 rounded px-4 py-2 text-gray-800 focus:outline-none focus:border-white-400">
                @error('iban') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Address Row -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-2">Cidade</label>
                <input type="text" wire:model="cidade"
                    class="w-full bg-white-100 border border-white-300 rounded px-4 py-2 text-gray-800 focus:outline-none focus:border-white-400">
                @error('cidade') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-2">Rua</label>
                <input type="text" wire:model="rua"
                    class="w-full bg-white-100 border border-white-300 rounded px-4 py-2 text-gray-800 focus:outline-none focus:border-white-400">
                @error('rua') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-2">Edifício</label>
                <input type="text" wire:model="edificio"
                    class="w-full bg-white-100 border border-white-300 rounded px-4 py-2 text-gray-800 focus:outline-none focus:border-white-400">
                @error('edificio') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Location -->
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-medium mb-2">Localização</label>
            <input type="text" wire:model="localizacao"
                class="bg-white-100 border border-white-300 rounded px-4 py-2 text-gray-800 w-64 focus:outline-none focus:border-white-400">
            @error('localizacao') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Region -->
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-medium mb-2">Regime</label>
            <input type="text" wire:model="regime"
                class="bg-white-100 border border-white-300 rounded px-4 py-2 text-gray-800 w-64 focus:outline-none focus:border-white-400">
            @error('regime') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-medium flex items-center gap-2">
                Salvar Informações
            </button>
      
        </div>

        @if (session()->has('message'))
        <div class="mt-4 text-green-600">
            {{ session('message') }}
        </div>
        @endif
    </form>
</div>