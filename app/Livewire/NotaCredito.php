<?php

namespace App\Livewire;

use App\Models\Fatura;
use App\Models\Recibo;
use Livewire\Component;

class NotaCredito extends Component
{
    public $start_date;
    public $end_date;

    public function mount()
    {
        $this->end_date = $this->end_date ?? now()->format('Y-m-d');
        $this->start_date = $this->start_date ?? now()->subMonth()->format('Y-m-d');
        $this->normalizeDates();
    }

    public function updatedStartDate()
    {
        $this->normalizeDates();
    }

    public function updatedEndDate()
    {
        $this->normalizeDates();
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

    public function render()
    {
        $start = $this->start_date . ' 00:00:00';
        $end = $this->end_date . ' 23:59:59';

        // ✅ BUSCAR FATURAS RETIFICADAS
        $faturasRetificadas = Fatura::query()
            ->where('retificada', true)
            ->whereBetween('data_retificacao', [$start, $end])
            ->with(['cliente', 'user', 'faturaRetificacao'])
            ->orderByDesc('data_retificacao')
            ->get();

        // ✅ BUSCAR FATURAS ANULADAS
        $faturasAnuladas = Fatura::query()
            ->where('anulada', true)
            ->whereBetween('data_anulacao', [$start, $end])
            ->with(['cliente', 'anuladaPor'])
            ->orderByDesc('data_anulacao')
            ->get();

        // ✅ BUSCAR RECIBOS RETIFICADOS
        $recibosRetificados = Recibo::query()
            ->where('retificado', true)
            ->whereBetween('data_retificacao', [$start, $end])
            ->with(['cliente', 'user', 'reciboRetificacao'])
            ->orderByDesc('data_retificacao')
            ->get();

        // ✅ BUSCAR RECIBOS ANULADOS
        $recibosAnulados = Recibo::query()
            ->where('anulado', true)
            ->whereBetween('data_anulacao', [$start, $end])
            ->with(['cliente', 'anuladoPor'])
            ->orderByDesc('data_anulacao')
            ->get();

        return view('livewire.nota-credito', [
            'dados' => (object) [
                'faturas_retificadas' => $faturasRetificadas,
                'faturas_anuladas' => $faturasAnuladas,
                'recibos_retificados' => $recibosRetificados,
                'recibos_anulados' => $recibosAnulados,
            ]
        ]);
    }
}