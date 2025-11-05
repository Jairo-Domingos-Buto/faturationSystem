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

    protected $paginationTheme = 'tailwind';
    protected $queryString = ['start_date', 'end_date', 'page'];

    public function mount()
    {
        $this->end_date = $this->end_date ?? now()->format('Y-m-d');
        $this->start_date = $this->start_date ?? now()->subMonth()->format('Y-m-d');
        $this->normalizeDates();
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

        // Se start_date for maior que end_date, troca-os
        if (strtotime($this->start_date) > strtotime($this->end_date)) {
            [$this->start_date, $this->end_date] = [$this->end_date, $this->start_date];
        }
    }

    public function delete($id)
    {
        $recibo = Recibo::find($id);

        if ($recibo) {
            $recibo->delete();
            session()->flash('message', 'Recibo eliminado com sucesso.');
        }
    }

    public function render()
    {
        $query = Recibo::query();

        // Aplica filtro de datas
        if ($this->start_date && $this->end_date) {
            $start = $this->start_date . ' 00:00:00';
            $end = $this->end_date . ' 23:59:59';
            $query->whereBetween('created_at', [$start, $end]);
        } else {
            if ($this->start_date) {
                $query->whereDate('created_at', '>=', $this->start_date);
            }
            if ($this->end_date) {
                $query->whereDate('created_at', '<=', $this->end_date);
            }
        }

        // Carrega relações e pagina
        $recibos = $query
            ->with(['fatura', 'cliente', 'user'])
            ->orderByDesc('created_at')
            ->paginate(15);

        // Calcula estatísticas
        $totalRecibos = $recibos->total();
        $somaValores = $query->sum('valor');
        
        // Conta por método de pagamento
        $recibosDinheiro = Recibo::query()
            ->when($this->start_date && $this->end_date, function($q) {
                $q->whereBetween('created_at', [
                    $this->start_date . ' 00:00:00',
                    $this->end_date . ' 23:59:59'
                ]);
            })
            ->where('metodo_pagamento', 'dinheiro')
            ->count();

        $recibosMulticaixa = Recibo::query()
            ->when($this->start_date && $this->end_date, function($q) {
                $q->whereBetween('created_at', [
                    $this->start_date . ' 00:00:00',
                    $this->end_date . ' 23:59:59'
                ]);
            })
            ->where('metodo_pagamento', 'multicaixa')
            ->count();

        // Busca dados da empresa
        $empresa = DadosEmpresa::first();

        return view('livewire.recibo-list', [
            'recibos' => $recibos,
            'empresa' => $empresa,
            'totalRecibos' => $totalRecibos,
            'somaValores' => $somaValores,
            'recibosDinheiro' => $recibosDinheiro,
            'recibosMulticaixa' => $recibosMulticaixa,
        ]);
    }
}