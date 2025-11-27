<?php

namespace App\Livewire;

use App\Models\DadosEmpresa;
use App\Models\Recibo;
use Livewire\Component;
use Livewire\WithPagination;

class ReciboList extends Component
{
    use WithPagination;

    public $start_date;

    public $end_date;

    public $empresa;

    protected $paginationTheme = 'tailwind';

    protected $queryString = ['start_date', 'end_date', 'page'];

    // Listener para atualizar a lista após uma anulação feita no Modal Global
    protected $listeners = ['reciboAnulado' => '$refresh'];

    public function mount()
    {
        $this->normalizeDates();
        $this->empresa = DadosEmpresa::first();
    }

    public function updatedStartDate()
    {
        $this->normalizeDates();
        $this->resetPage();
    }

    public function updatedEndDate()
    {
        $this->normalizeDates();
        $this->resetPage();
    }

    protected function normalizeDates()
    {
        $this->start_date = $this->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $this->end_date = $this->end_date ?? now()->endOfMonth()->format('Y-m-d');
    }

    /**
     * ✅ Abrir Modal de Anulação
     * Este método agora dispara o evento correto para o modal global
     */
    public function confirmarAnulacao($id)
    {
        $recibo = Recibo::find($id);

        if (! $recibo || ! $recibo->pode_ser_anulado) {
            session()->flash('error', 'Este recibo não pode ser anulado.');

            return;
        }

        // Dispara evento para o JavaScript (alpine/script) que controla o modal
        $this->dispatch('abrirModalAnulacao',
            tipo: 'recibo',
            id: $recibo->id,
            numero: $recibo->numero
        );
    }

    public function render()
    {
        $query = Recibo::query()
            ->with(['cliente', 'user']) // Eager loading
            ->where('retificado', false) // Apenas recibos válidos
            ->where('anulado', false);

        if ($this->start_date && $this->end_date) {
            $query->whereBetween('data_emissao', [
                $this->start_date.' 00:00:00',
                $this->end_date.' 23:59:59',
            ]);
        }

        $recibos = $query->orderByDesc('created_at')->paginate(15);

        // Totais para os Cards
        $totalRecibos = $recibos->total();
        $somaValores = $query->clone()->sum('valor');

        // Contagem por método (usando a query filtrada)
        $qClone = $query->clone();
        $recibosDinheiro = (clone $qClone)->where('metodo_pagamento', 'dinheiro')->count();
        $recibosMulticaixa = (clone $qClone)->where('metodo_pagamento', 'cartao')->count();
        $recibosTransf = (clone $qClone)->where('metodo_pagamento', 'transferencia')->count();

        return view('livewire.recibo-list', [
            'recibos' => $recibos,
            'totalRecibos' => $totalRecibos,
            'somaValores' => $somaValores,
            'recibosDinheiro' => $recibosDinheiro,
            'recibosMulticaixa' => $recibosMulticaixa,
            'recibosTransf' => $recibosTransf,
        ]);
    }
}
