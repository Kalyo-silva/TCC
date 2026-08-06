<?php

use Livewire\Component;
use App\Models\instrumento;
use Livewire\Attributes\On;

new class extends Component
{
    public $id;
    public $titulo;
    public $ano;

    protected $listeners = ['postInsert' => '$refresh'];

    #[On('Detail')]
    public function getIdInstrumento($id){
        $this->id = $id;

        $instrumento = instrumento::find($this->id);

        if ($instrumento){
            $this->titulo = $instrumento->titulo;
            $this->ano = $instrumento->ano;
        }
    }   

    public function select(int $id){
        $this->dispatch('DetailDimensao', id : $id);
    }
};
?>

<flux:modal name="details" class="max-w-4xl w-full">
    <div class="flex items-center gap-2">
        <flux:icon.information-circle/>
        <flux:heading size="lg">Detalhes do Instrumento de Avaliação</flux:heading>
    </div>
    
    <div class="flex flex-col mt-8">
        <div class="flex gap-4">
            <div class="w-8/10">
                <flux:input label="Título" placeholder="Titulo..." wire:model='titulo'/>
            </div>
            <div class="w-2/10">
                <flux:input label="Ano" placeholder="Ano..." wire:model='ano'/>
            </div>
        </div>

        <div class="flex gap-4 justify-between items-end my-4">
            <flux:text>Lista de Dimensões</flux:text>
            <flux:modal.trigger name="createDimensao">
                <flux:button icon="plus" size="sm" variant="ghost" wire:click='select({{ $this->id }})'></flux:button>
            </flux:modal.trigger>
        </div>
        
        <livewire:dimensao.list :instrumento_id="$this->id" />     

        <livewire:dimensao.create />
    </div>
</flux:modal>