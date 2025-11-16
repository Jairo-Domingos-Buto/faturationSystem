<?php

namespace App\Livewire;

use App\Models\Fatura;
use App\Models\Recibo;
use Livewire\Component;

class NotaCredito extends Component
{
    public $faturas = [];
    public $recibos = [];
    public $dados = [];
    public $start_date;
    public $end_date;

    public function mount()
    {
        $this->start_date = now()->subMonth()->format('Y-m-d');
        $this->end_date = now()->format('Y-m-d');
        $this->carregarDados();
    }

    public function updatedStartDate()
    {
        $this->carregarDados();
    }

    public function updatedEndDate()
    {
        $this->carregarDados();
    }

    public function carregarDados()
    {
        // Buscar documentos retificados
        $this->faturas = Fatura::retificadas()
            ->with(['cliente', 'user', 'faturaOriginal'])
            ->when($this->start_date && $this->end_date, function($query) {
                $query->whereBetween('data_retificacao', [
                    $this->start_date . ' 00:00:00',
                    $this->end_date . ' 23:59:59'
                ]);
            })
            ->latest('data_retificacao')
            ->get();

        $this->recibos = Recibo::retificados()
            ->with(['cliente', 'user', 'reciboOriginal'])
            ->when($this->start_date && $this->end_date, function($query) {
                $query->whereBetween('data_retificacao', [
                    $this->start_date . ' 00:00:00',
                    $this->end_date . ' 23:59:59'
                ]);
            })
            ->latest('data_retificacao')
            ->get();

        $this->dados = (object)[
            "faturas" => $this->faturas,
            "recibos" => $this->recibos,
        ];
    }

    public function retificar($faturaId)
    {
        session()->put('fatura_retificar_id', $faturaId);
        return redirect()->route('admin.pov');
    }

    public function retificarRecibo($reciboId)
    {
        session()->put('recibo_retificar_id', $reciboId);
        return redirect()->route('admin.pov');
    }

    public function visualizarDocumento($tipo, $id)
    {
        if ($tipo === 'fatura') {
            return redirect()->route('admin.fatura.download', ['fatura' => $id]);
        } else {
            return redirect()->route('admin.recibo.download', ['recibo' => $id]);
        }
    }

    public function verDetalhes($tipo, $id)
    {
        // Implementar modal ou página de detalhes
        if ($tipo === 'fatura') {
            $documento = Fatura::with(['cliente', 'user', 'faturaOriginal', 'items.produto'])->find($id);
        } else {
            $documento = Recibo::with(['cliente', 'user', 'reciboOriginal', 'items.produto'])->find($id);
        }
        
        // Aqui você pode abrir um modal ou redirecionar para página de detalhes
        $this->dispatch('abrirModalDetalhes', documento: $documento, tipo: $tipo);
    }

    public function render()
    {
        return view('livewire.nota-credito', [
            'dados' => $this->dados
        ]);
    }
}