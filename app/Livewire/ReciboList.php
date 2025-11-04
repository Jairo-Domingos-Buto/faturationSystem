<?php

namespace App\Livewire;

use App\Models\Recibo;
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

    /**
     * Garante que start_date e end_date estão em formato correto e que start <= end.
     * Se start > end, faz swap para evitar queries inválidas.
     */
    protected function normalizeDates()
    {
        if (! $this->start_date) {
            $this->start_date = now()->subMonth()->format('Y-m-d');
        }

        if (! $this->end_date) {
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

        // Se ambas as datas estão definidas, usa whereBetween com intervalo completo do dia
        if ($this->start_date && $this->end_date) {
            $start = $this->start_date.' 00:00:00';
            $end = $this->end_date.' 23:59:59';
            $query->whereBetween('created_at', [$start, $end]);
        } else {
            if ($this->start_date) {
                $query->whereDate('created_at', '>=', $this->start_date);
            }
            if ($this->end_date) {
                $query->whereDate('created_at', '<=', $this->end_date);
            }
        }

        $recibos = $query->with(['fatura', 'cliente', 'user'])->orderByDesc('created_at')->paginate(15);

        return view('livewire.recibo-list', compact('recibos'));
    }
}
