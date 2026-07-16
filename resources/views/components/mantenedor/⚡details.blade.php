<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\models\mantenedor;
use Flux\Flux;

new class extends Component
{
    public $id;
    public $nome;
    public $cidade;
    public $uf;
    public $bairro;
    public $cep;
    public $logradouro;

    #[On('MantenedorDetail')]
    public function getIdMantenedor($id){
        $this->id = $id;

        $mantenedor = mantenedor::find($this->id);

        if ($mantenedor){
            $this->nome = $mantenedor->nome;
            $this->cidade = $mantenedor->cidade;
            $this->uf = $mantenedor->uf;
            $this->bairro = $mantenedor->bairro;
            $this->cep = $mantenedor->cep;
            $this->logradouro = $mantenedor->logradouro;
            
        }
    }
    
    public function select(int $id){
        Flux::modal('details')->close();
        $this->dispatch('MantenedorDetail', id : $id);
    }
};
?>

<div class="flex flex-col gap-4 mt-8">
    <flux:input readonly label="Nome" placeholder="Nome..." wire:model='nome'/>
    <div class="flex gap-4">
        <div class="w-8/10">
            <flux:input readonly label="Cidade" placeholder="Cidade..." wire:model="cidade"/>
        </div>
        <div class="w-2/10">
            <flux:input readonly label="UF" placeholder="UF..." wire:model="uf" maxlength="2"/>
        </div>
    </div>
    <div class="flex gap-4">
        <flux:input readonly label="Bairro" placeholder="Bairro..." wire:model="bairro"/>
        <flux:input readonly label="CEP" placeholder="CEP..." wire:model="cep"/>
    </div>
    <flux:input readonly label="Logradouro" placeholder="Logradouro..." wire:model="logradouro"/>

    <div class="flex items-center gap-2 justify-end mt-4">
        <flux:modal.trigger name="remove">
            <flux:button icon="trash" wire:click='select({{ $id }})'>Excluir</flux:button>
        </flux:modal.trigger>
        
        <flux:modal.trigger name="edit">
            <flux:button icon="pencil-square" wire:click='select({{ $id }})'>Editar</flux:button>
        </flux:modal.trigger>
    </div>
    
</div>