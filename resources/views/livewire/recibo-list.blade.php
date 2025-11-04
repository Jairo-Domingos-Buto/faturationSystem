<main class='bg-white p-3 h-full'>
    <div>
        <h2>recibos</h2>
    </div>
    <header class='bg-gray-50 rounded border p-3'>
        <div class='flex justify-between items-center w-full'>
            {{-- filter de datas --}}
            <div>
                <input type="date" name="data" placeholder='' id="data"
                    class='text-black border-1 p-2 rounded border-gray-400'> ->

                <input type="date" name="data" id="data" class='text-black border-1 p-2 rounded border-gray-400'>

            </div>

            <div>
                <button class='flex justify-center w-[150px] bg-[#3c83f6] text-white rounded p-2 items-center'>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="mr-2 h-4 w-4">
                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                        <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                        <path d="M10 9H8"></path>
                        <path d="M16 13H8"></path>
                        <path d="M16 17H8"></path>
                    </svg>
                    <span>Guardar Fatura</span>
                </button>
            </div>

        </div>
    </header>
    {{-- estatitisca --}}
    <div class='mt-3 flex  items-center justify-between'>
        <div class='p-2.5 rounded bg-gray-100 border text-black '>
            <span><strong> Recibos geradas:</strong> {{ $recibos->count() }}</span>
        </div>
        <div class='p-2.5 rounded bg-gray-100 border text-black'>
            <span><strong> Total de recibos (AOA):</strong> {{ $recibos->sum('valor') }} KZ</span>
        </div>
        <div class='p-2.5 rounded bg-gray-100 border text-black '>
            <span><strong> recibos em DInheiro:</strong> 0</span>
        </div>
        <div class='p-2.5 rounded bg-gray-100 border text-black '>
            <span><strong> recibos Por Multicaixa:</strong> 0</span>
        </div>
    </div>

    {{-- tabelas com os Dados --}}

    <div class='p-2'>


        <!-- Basic Bootstrap Table -->


        <table class="table">
            <thead>
                <tr>
                    <th>EmissAão</th>
                    <th>Número</th>
                    <th>Cliente</th>
                    <th>Usuário</th>
                    
                    <th>Preço</th>
                    <th>Acções</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse ($recibos as $recibo)
                <tr>
                    <td>{{ $recibo->created_at }}</td>
                    <td>{{ $recibo->numero }}</td>
                    <td>{{ $recibo->cliente?->nome ?? 'Sem nome' }}</td>
                    <td>{{ $recibo->user->name }}</td>
                    <td>{{ $recibo->valor }}</td>
                    <td>{{ $recibo->total }}</td>
                    <td><button wire:click="delete({{ $recibo->id }})" class='bg-red-500 text-white rounded p-1'
                        onclick="return confirm('Deseja realmente eliminar este recibo?')">
                        Eliminar
                    </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan='7' class='text-center'>Sem Dados encontrados</td>
                </tr>
                @endforelse
            </tbody>
        </table>


        <!-- Content wrapper -->
    </div>

</main>