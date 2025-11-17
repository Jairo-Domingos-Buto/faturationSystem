<?php

namespace App\Livewire;

use App\Models\Fatura;
use App\Models\DadosEmpresa;
use Livewire\Component;
use Livewire\WithPagination;

class FaturaList extends Component
{
    use WithPagination;

    public $start_date;
    public $end_date;
    public $empresa;

    protected $paginationTheme = 'tailwind';
    protected $queryString = ['start_date', 'end_date', 'page'];

    // ✅ Listener para recarregar após anulação
    protected $listeners = ['faturaAnulada' => '$refresh'];

    public function mount()
    {
        $this->end_date = $this->end_date ?? now()->format('Y-m-d');
        $this->start_date = $this->start_date ?? now()->subMonth()->format('Y-m-d');
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
        if (!$this->start_date) {
            $this->start_date = now()->subMonth()->format('Y-m-d');
        }

        if (!$this->end_date) {
            $this->end_date = now()->format('Y-m-d');
        }

        if (strtotime($this->start_date) > strtotime($this->end_date)) {
            [$this->start_date, $this->end_date] = [$this->end_date, $this->start_date];
        }
    }

    /**
     * ✅ MÉTODO ATUALIZADO: Abre modal via JavaScript
     */
    public function delete($faturaId)
    {
        try {
            $fatura = Fatura::findOrFail($faturaId);
            
            if (!$fatura->pode_ser_anulada) {
                session()->flash('error', 'Esta fatura não pode ser anulada.');
                return;
            }

            // ✅ Dispara evento JavaScript para abrir modal
            $this->dispatch('abrirModalAnulacao',
                tipo: 'fatura',
                id: $faturaId,
                numero: $fatura->numero
            );

        } catch (\Exception $e) {
            session()->flash('error', 'Erro: ' . $e->getMessage());
        }
    }

    public function eliminar($faturaId)
    {
        $fatura = Fatura::find($faturaId);

        if ($fatura) {
            $fatura->delete();
            session()->flash('message', 'Fatura eliminada com sucesso.');
        }
    }

    public function render()
    {
        // ✅ MOSTRA APENAS FATURAS ATIVAS (não retificadas, não anuladas)
        $query = Fatura::query()
            ->where('retificada', false)
            ->where('anulada', false);

        if ($this->start_date && $this->end_date) {
            $start = $this->start_date.' 00:00:00';
            $end = $this->end_date.' 23:59:59';
            $query->whereBetween('created_at', [$start, $end]);
        }

        $faturas = $query
            ->with(['cliente', 'user'])
            ->orderByDesc('created_at')
            ->paginate(15);

        $totalFaturas = $faturas->total();
        $somaSubtotal = $query->clone()->sum('subtotal');
        $somaImpostos = $query->clone()->sum('total_impostos');
        $somaTotal = $query->clone()->sum('total');

        return view('livewire.fatura-list', [
            'faturas' => $faturas,
            'empresa' => $this->empresa,
            'totalFaturas' => $totalFaturas,
            'somaSubtotal' => $somaSubtotal,
            'somaImpostos' => $somaImpostos,
            'somaTotal' => $somaTotal,
        ]);
    }
}