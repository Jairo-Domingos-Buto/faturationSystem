<?php

namespace App\Livewire;

use App\Models\Recibo;
use App\Models\DadosEmpresa;
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

    // ✅ Listener para recarregar após anulação
    protected $listeners = ['reciboAnulado' => '$refresh'];

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
    public function delete($reciboId)
    {
        try {
            $recibo = Recibo::findOrFail($reciboId);
            
            if (!$recibo->pode_ser_anulado) {
                session()->flash('error', 'Este recibo não pode ser anulado.');
                return;
            }
            

            // ✅ Dispara evento JavaScript para abrir modal
            $this->dispatch('abrirModalAnulacao',
                tipo: 'recibo',
                id: $reciboId,
                numero: $recibo->numero
            );

        } catch (\Exception $e) {
            session()->flash('error', 'Erro: ' . $e->getMessage());
        }
    }

    public function eliminar($reciboId)
    {
        $recibo = Recibo::find($reciboId);

        if ($recibo) {
            $recibo->delete();
            session()->flash('message', 'Recibo eliminado com sucesso.');
        }
    }

    public function render()
    {
        // ✅ MOSTRA APENAS RECIBOS ATIVOS (não retificados, não anulados)
        $query = Recibo::query()
            ->where('retificado', false)
            ->where('anulado', false);

        if ($this->start_date && $this->end_date) {
            $start = $this->start_date.' 00:00:00';
            $end = $this->end_date.' 23:59:59';
            $query->whereBetween('created_at', [$start, $end]);
        }

        $recibos = $query
            ->with(['fatura', 'cliente', 'user'])
            ->orderByDesc('created_at')
            ->paginate(15);

        $totalRecibos = $recibos->total();
        $somaValores = $query->clone()->sum('valor');
        $recibosDinheiro = $query->clone()->where('metodo_pagamento', 'dinheiro')->count();
        $recibosMulticaixa = $query->clone()->where('metodo_pagamento', 'multicaixa')->count();

        return view('livewire.recibo-list', [
            'recibos' => $recibos,
            'empresa' => $this->empresa,
            'totalRecibos' => $totalRecibos,
            'somaValores' => $somaValores,
            'recibosDinheiro' => $recibosDinheiro,
            'recibosMulticaixa' => $recibosMulticaixa,
        ]);
    }
}