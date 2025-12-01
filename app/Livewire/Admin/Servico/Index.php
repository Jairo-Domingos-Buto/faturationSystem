<?php

namespace App\Livewire\Admin\Servico;

use App\Models\Servico;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    // Campos do formulário com Validação Automática
    #[Rule('required|min:3', as: 'descrição')]
    public $descricao = '';

    #[Rule('required|numeric|min:0', as: 'preco_venda')]
    public $preco_venda = '';

    public $servicoId = null; // Para saber se é Edição ou Criação

    public $search = '';      // Barra de pesquisa (Extra: Bônus)

    // Escuta eventos para limpar erros
    protected $listeners = ['resetModal' => 'resetInput'];

    public function render()
    {
        $servicos = Servico::where('descricao', 'like', '%'.$this->search.'%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.admin.servico.index', [
            'servicos' => $servicos,
        ]);
    }

    // Abre o modal para CRIAR
    public function create()
    {
        $this->resetInput();
        $this->dispatch('open-modal');
    }

    // Abre o modal para EDITAR e preenche os dados
    public function edit($id)
    {
        $servico = Servico::findOrFail($id);
        $this->servicoId = $servico->id;
        $this->descricao = $servico->descricao;
        $this->preco_venda = $servico->preco_venda;

        $this->resetValidation();
        $this->dispatch('open-modal', titulo: 'Editar Serviço'); // Dispara evento p/ JS abrir modal e trocar título
    }

    // Salva ou Atualiza (baseado no servicoId)
    public function save()
    {
        $this->validate();

        if ($this->servicoId) {
            // Atualizar
            $servico = Servico::find($this->servicoId);
            $servico->update([
                'descricao' => $this->descricao,
                'preco_venda' => $this->preco_venda,
            ]);
            session()->flash('success', 'Serviço atualizado com sucesso!');
        } else {
            // Criar
            Servico::create([
                'descricao' => $this->descricao,
                'preco_venda' => $this->preco_venda,
            ]);
            session()->flash('success', 'Serviço criado com sucesso!');
        }

        $this->dispatch('close-modal'); // Manda o JS fechar o modal
        $this->resetInput();
    }

    public function delete($id)
    {
        Servico::find($id)->delete();
        session()->flash('success', 'Serviço excluído com sucesso!');
    }

    public function resetInput()
    {
        $this->descricao = '';
        $this->preco_venda = '';
        $this->servicoId = null;
        $this->resetValidation();
    }
}
