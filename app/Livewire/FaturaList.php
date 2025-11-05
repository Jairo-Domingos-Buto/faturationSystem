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
        $fatura = Fatura::find($id);

        if ($fatura) {
            $fatura->delete();
            session()->flash('message', 'Fatura eliminada com sucesso.');
        }
    }

    public function render()
    {
        $query = Fatura::query();

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
        $faturas = $query
            ->with(['cliente', 'user'])
            ->orderByDesc('created_at')
            ->paginate(15);

        // Calcula estatísticas
        $totalFaturas = $faturas->total();
        $somaSubtotal = Fatura::query()
            ->when($this->start_date && $this->end_date, function($q) {
                $q->whereBetween('created_at', [
                    $this->start_date . ' 00:00:00',
                    $this->end_date . ' 23:59:59'
                ]);
            })
            ->sum('subtotal');
        
        $somaImpostos = Fatura::query()
            ->when($this->start_date && $this->end_date, function($q) {
                $q->whereBetween('created_at', [
                    $this->start_date . ' 00:00:00',
                    $this->end_date . ' 23:59:59'
                ]);
            })
            ->sum('total_impostos');

        $somaTotal = Fatura::query()
            ->when($this->start_date && $this->end_date, function($q) {
                $q->whereBetween('created_at', [
                    $this->start_date . ' 00:00:00',
                    $this->end_date . ' 23:59:59'
                ]);
            })
            ->sum('total');

        // Conta por estado
        $faturasEmitidas = Fatura::query()
            ->when($this->start_date && $this->end_date, function($q) {
                $q->whereBetween('created_at', [
                    $this->start_date . ' 00:00:00',
                    $this->end_date . ' 23:59:59'
                ]);
            })
            ->where('estado', 'emitida')
            ->count();

        $faturasPagas = Fatura::query()
            ->when($this->start_date && $this->end_date, function($q) {
                $q->whereBetween('created_at', [
                    $this->start_date . ' 00:00:00',
                    $this->end_date . ' 23:59:59'
                ]);
            })
            ->where('estado', 'paga')
            ->count();

        // Busca dados da empresa
        $empresa = DadosEmpresa::first();

        return view('livewire.fatura-list', [
            'faturas' => $faturas,
            'empresa' => $empresa,
            'totalFaturas' => $totalFaturas,
            'somaSubtotal' => $somaSubtotal,
            'somaImpostos' => $somaImpostos,
            'somaTotal' => $somaTotal,
            'faturasEmitidas' => $faturasEmitidas,
            'faturasPagas' => $faturasPagas,
        ]);
    }
}