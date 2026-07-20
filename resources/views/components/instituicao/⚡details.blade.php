<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\instituicao;

new class extends Component
{
    public $id;
    public $logo;
    public $nome;
    public $sigla;
    public $cidade;
    public $uf;
    public $bairro;
    public $cep;
    public $logradouro;
    public $mantenedor;
    
    #[On('Detail')]
    public function getIdinstituicao($id){
        $this->id = $id;

        $instituicao = instituicao::with('mantenedor')->find($this->id);

        if ($instituicao){
            $this->nome = $instituicao->nome;
            $this->cidade = $instituicao->cidade;
            $this->uf = $instituicao->uf;
            $this->bairro = $instituicao->bairro;
            $this->cep = $instituicao->cep;
            $this->logradouro = $instituicao->logradouro;
            $this->mantenedor = $instituicao->mantenedor->nome;
            $this->sigla = $instituicao->sigla;
            $this->logo = asset('storage/img_instituicoes/'.$instituicao->logo);
        }
    }
    

    public function selectEdit(){
        $this->dispatch('DetailEdit', id : $this->id);
    }
};
?>

<flux:modal name="details" class="max-w-4xl">
    <div class="flex items-center gap-2">
        <flux:icon.information-circle/>
        <flux:heading size="lg">Detalhes da Instituição</flux:heading>
    </div>

    <div class="flex flex-col gap-4 mt-4">
        <div class="flex items-center gap-4">
            <label for="logo" class="h-30 w-30 mt-6">
                <div class="cursor-pointer h-full rounded-lg border border-zinc-600 w-full bg-zinc-700 items-center justify-center flex overflow-hidden">
                    @if ($this->logo)
                        <img class="w-full h-full object-cover" src="{{ $this->logo }}">
                    @else
                        <flux:icon.camera class="size-12"/>
                    @endif
                </div>
            </label>
            <div class="flex flex-col gap-4">
                <div class="flex gap-4">
                    <div class="w-6/10">
                        <flux:input label="Nome" readonly placeholder="Nome..." wire:model='nome'/>
                    </div>
                    <div class="w-4/10">
                        <flux:input label="Sigla" readonly placeholder="Sigla..." wire:model='sigla'/>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="w-8/10">
                        <flux:input label="Cidade" readonly placeholder="Cidade..." wire:model="cidade"/>
                    </div>
                    <div class="w-2/10">
                        <flux:input label="UF" readonly placeholder="UF..." wire:model="uf" maxlength="2"/>
                    </div>
                </div>
            </div>
        </div>
        <flux:input icon="building-library" readonly placeholder="Mantenedor..." label="Mantenedor" wire:model="mantenedor"/>
        <div class="flex gap-4">
            <div class="w-8/10">
                <flux:input placeholder="Bairro..." readonly label="Bairro" wire:model="bairro"/>
            </div>
            <flux:input placeholder="CEP..."  readonly label="CEP" mask="99999-999" wire:model="cep"/>
        </div>
        <flux:input placeholder="Logradouro..."  readonly label="Logradouro" wire:model="logradouro"/>
        <div class="flex flex-row-reverse gap-4">
            <flux:modal.trigger name="edit"> 
                <flux:button type="submit" class="mt-4" icon="pencil-square" wire:click="selectEdit()">Editar</flux:button> 
            </flux:modal.trigger>
            <flux:modal.trigger name="remove"> 
                <flux:button type="submit" class="mt-4" icon="trash">Remover</flux:button> 
            </flux:modal.trigger>
        </div>
    </div>

</flux:modal>