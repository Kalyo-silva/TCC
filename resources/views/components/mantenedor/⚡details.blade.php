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
    public $instituicoes;

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
            $this->instituicoes = $mantenedor->instituicoes()->get();
        }
    }
    
    public function select(int $id){
        $this->dispatch('MantenedorDetail', id : $id);
    }
};
?>

<flux:modal name="details" class="max-w-4xl">
    <div class="flex items-center gap-2">
        <flux:icon.information-circle/>
        <flux:heading size="lg">Detalhe do Mantenedor</flux:heading>
    </div>
    
    <div class="flex gap-4">
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
        </div>
        <flux:card>  
            <flux:heading class="text-nowrap mb-2" size="lg">Instituições Vinculadas</flux:heading>
            @if($this->instituicoes)
                <div class="flex flex-col gap-2 overflow-y-scroll max-h-64">
                    
                    @foreach ($this->instituicoes as $inst)
                        <div class="flex gap-2 items-center pr-8 cursor-pointer rounded-lg hover:bg-zinc-600">
                            <img src="{{asset('storage/img_instituicoes/'.$inst->logo)}}" alt="logo" class="size-10 rounded-lg">
                            <div>   
                                <flux:heading>{{$inst->nome}}</flux:heading>
                                <flux:text>{{$inst->sigla}}</flux:text>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </flux:card>
    </div>
    
    <div class="flex items-center gap-4 justify-end mt-4">
        <flux:modal.trigger name="remove">
            <flux:button icon="trash" wire:click='select({{ $id }})'>Excluir</flux:button>
        </flux:modal.trigger>
        
        <flux:modal.trigger name="edit">
            <flux:button icon="pencil-square" wire:click='select({{ $id }})'>Editar</flux:button>
        </flux:modal.trigger>
    </div>
</flux:modal>
