<?php

namespace App\Livewire;

use App\Models\DadosEmpresa;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ConfiguracoesEmpresa extends Component
{
    #[Validate('required|max:255')]
    public $name = "";
    #[Validate('required|max:255')]
    public $nif = "";
    #[Validate('required|max:255')]
    public $telefone = "";
    #[Validate('required|max:255|email')]
    public $email = "";
    #[Validate('required|max:255|url')]
    public $website = "";
    #[Validate('required|max:255')]
    public $banco = "";
    #[Validate('required|max:255')]
    public $iban = "";
    #[Validate('required|max:255')]
    public $cidade = "";
    #[Validate('required|max:255')]
    public $rua = "";
    #[Validate('required|max:255')]
    public $edificio = "";
    #[Validate('required|max:255')]
    public $localizacao = "";
    #[Validate('required|max:255')]
    public $regime = "";

    public $empresa;

    public function mount()
    {
        $this->empresa = DadosEmpresa::first();
        if ($this->empresa) {
            $this->name = $this->empresa->name;
            $this->nif = $this->empresa->nif;
            $this->telefone = $this->empresa->telefone;
            $this->email = $this->empresa->email;
            $this->website = $this->empresa->website;
            $this->banco = $this->empresa->nomeDoBanco;
            $this->iban = $this->empresa->iban;
            $this->cidade = $this->empresa->cidade;
            $this->rua = $this->empresa->rua;
            $this->edificio = $this->empresa->edificio;
            $this->localizacao = $this->empresa->mucicipio;
            $this->regime = $this->empresa->regime;
        }
    }

    public function save()
    {
        $this->validate();

        DadosEmpresa::updateOrCreate(
            ['id' => $this->empresa->id ?? null],
            [
                'name' => $this->name,
                'nif' => $this->nif,
                'telefone' => $this->telefone,
                'email' => $this->email,
                'website' => $this->website,
                'nomeDoBanco' => $this->banco,
                'iban' => $this->iban,
                'cidade' => $this->cidade,
                'rua' => $this->rua,
                'edificio' => $this->edificio,
                'mucicipio' => $this->localizacao,
                'regime' => $this->regime,
            ]
        );

        session()->flash('message', 'Dados salvos com sucesso!');
    }

    public function render()
    {
        return view('livewire.configuracoes-empresa', [
            'empresa' => $this->empresa
        ]);
    }
}