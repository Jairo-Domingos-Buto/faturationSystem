<?php

namespace App\Livewire;

use App\Models\Cliente;
use Livewire\Component;

class Pov extends Component
{
    public $clientes = [];
    public $clienteSelecionado = null;
    public $showModal = false;
    public $searchTerm = '';

    public function mount()
    {
        $this->selecionarClientes();
    }

    public function selecionarClientes()
    {
        $this->clientes = Cliente::when($this->searchTerm, function ($query) {
            $query->where('nome', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('nif', 'like', '%' . $this->searchTerm . '%');
        })->get();
    }

    public function selecionarCliente($clienteId)
    {
        $cliente = Cliente::find($clienteId);
        $this->clienteSelecionado = $cliente ? $cliente->nome : null;
        $this->showModal = false;
        $this->dispatch('toggle-modal', show: false);
    }

    public function updatedSearchTerm()
    {
        $this->selecionarClientes();
    }

    public function updatedShowModal($value)
    {
        $this->dispatch('toggle-modal', show: $value);
    }

    public function render()
    {
        return view('livewire.pov');
    }
}
