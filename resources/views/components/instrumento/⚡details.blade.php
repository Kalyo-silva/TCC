<?php

use Livewire\Component;
use App\models\instrumento;
use Livewire\Attributes\On;

new class extends Component
{
    public $id;
    public $titulo;
    public $ano;

    #[On('Detail')]
    public function getIdInstrumento($id){
        $this->id = $id;

        $instrumento = instrumento::find($this->id);

        if ($instrumento){
            $this->titulo = $instrumento->titulo;
            $this->ano = $instrumento->ano;
        }
    }

};
?>

<flux:modal name="details" class="max-w-4xl w-full">
    <div class="flex items-center gap-2">
        <flux:icon.information-circle/>
        <flux:heading size="lg">Detalhes do Instrumento de Avaliação</flux:heading>
    </div>
    
    <div class="flex flex-col gap-8 mt-8">
        <div class="flex gap-4">
            <div class="w-8/10">
                <flux:input label="Título" placeholder="Titulo..." wire:model='titulo'/>
            </div>
            <div class="w-2/10">
                <flux:input label="Ano" placeholder="Ano..." wire:model='ano'/>
            </div>
        </div>
        <flux:separator text="Dimensões"/>
        <div>

        </div>
    </div>
</flux:modal>