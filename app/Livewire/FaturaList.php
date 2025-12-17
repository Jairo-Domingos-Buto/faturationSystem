<?php

namespace App\Livewire;

use App\Models\DadosEmpresa;
use App\Models\Fatura;
use Livewire\Component;
use Livewire\WithPagination;


class FaturaList extends Component
{
    use WithPagination;

    public $start_date;

    public $end_date;

    public $empresa;

    // ✅ NOVO: Filtro por Tipo de Documento
    public $filtro_tipo = 'todos'; // todos, FT, FR, FP e FT + FR

    protected $paginationTheme = 'tailwind';

    protected $queryString = ['start_date', 'end_date', 'filtro_tipo', 'page'];

    // ✅ Listener para recarregar
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

    public function updatedFiltroTipo()
    {
        $this->resetPage();
    } // Reset ao mudar filtro

    protected function normalizeDates()
    {
        if (! $this->start_date) {
            $this->start_date = now()->subMonth()->format('Y-m-d');
        }
        if (! $this->end_date) {
            $this->end_date = now()->format('Y-m-d');
        }
        if (strtotime($this->start_date) > strtotime($this->end_date)) {
            [$this->start_date, $this->end_date] = [$this->end_date, $this->start_date];
        }
    }

    /**
     * Abrir Modal de Anulação
     */
    public function delete($faturaId)
    {
        try {
            $fatura = Fatura::findOrFail($faturaId);

            if (! $fatura->pode_ser_anulada) {
                session()->flash('error', 'Este documento não pode ser anulado.');

                return;
            }

            $this->dispatch('abrirModalAnulacao',
                tipo: 'fatura', // A lógica de anulação no modal é genérica
                id: $faturaId,
                numero: $fatura->numero
            );

        } catch (\Exception $e) {
            session()->flash('error', 'Erro: '.$e->getMessage());
        }
    }

    /**
     * Converter Proforma em Fatura (Redireciona para o POV)
     */
    public function converterProforma($id)
    {
        // Envia para o POV carregando os dados da Proforma
        return redirect()->route('admin.pov', [
            'retificar_id' => $id,
            'tipo' => 'fatura',
            // O POV atual entende "retificar_id" como carregar dados.
            // Na prática, isso permite carregar os itens.
            // Depois, basta salvar como FT normal.
        ]);
    }

    public function render()
    {
        // Base Query: Não retificadas, não anuladas
        $query = Fatura::query()
            ->with(['cliente', 'user', 'faturaRetificacao']) // Eager Loading otimizado
            ->where('retificada', false)
            ->where('anulada', false);

if ($this->filtro_tipo !== 'todos') {
    $tipos = is_array($this->filtro_tipo)
        ? $this->filtro_tipo
        : explode(',', $this->filtro_tipo);

    $query->whereIn('tipo_documento', $tipos);
}
        // Paginação
        $faturas = $query->orderByDesc('created_at')->paginate(15);

        // ✅ CÁLCULO DE TOTAIS (Inteligente)
        // Só soma nos quadros de "Receita" o que for FT e FR.
        // Se o usuário filtrar por FP, os quadros mostram totais de Proformas.

        $queryTotais = $query->clone();

        // Se estiver vendo "todos", removemos Proformas do cálculo financeiro real
        if ($this->filtro_tipo === 'todos') {
            $queryTotais->where('tipo_documento', '!=', 'FP');
        }

        $totalDocumentos = $faturas->total();
        $somaSubtotal = $queryTotais->sum('subtotal');
        $somaImpostos = $queryTotais->sum('total_impostos');
        $somaTotal = $queryTotais->sum('total');

        return view('livewire.fatura-list', [
            'faturas' => $faturas,
            'totalFaturas' => $totalDocumentos,
            'somaSubtotal' => $somaSubtotal,
            'somaImpostos' => $somaImpostos,
            'somaTotal' => $somaTotal,
        ]);
    }
}
