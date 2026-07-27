<?php

use Livewire\Component;
use App\models\instrumento;
use App\models\dimensao;
use Livewire\Attributes\On;
use Flux\Flux;

new class extends Component
{
    public $instrumento_id;
    public $tituloInstrumento;
    public $descricao;
    public $sequencia;

    #[On('DetailDimensao')]
    public function getIdInstrumento($id){
        $this->instrumento_id = $id;

        $instrumento = instrumento::find($this->instrumento_id);

        if ($instrumento){
            $this->tituloInstrumento = $instrumento->titulo;
        
            $this->sequencia = dimensao::where('instrumento_id', $this->instrumento_id)->max('sequencia')+1;
        }
    }
    
    public function save(){
        $dimensao = new dimensao();


        $dimensao->instrumento_id = $this->instrumento_id;
        $dimensao->descricao = $this->descricao;
        $dimensao->sequencia = $this->sequencia;

        $validated = $this->validate([
            "descricao" => "required",
            "sequencia" => "required|integer",
            "instrumento_id" => "required|integer",
            ]);

        if ($validated){
            try{
                if ($dimensao->save()){
                    $this->dispatch('postInsert');
                    Flux::toast(variant : "success", text: 'Registro criado com sucesso!');
                    Flux::modal('createDimensao')->close();
                }
            }
            catch (Throwable $e){  
                Flux::toast(variant : "danger", heading: 'Falha ao criar o registro...', text : $e->getMessage());
            }
        }
    }
};
?>

<flux:modal name="createDimensao" class="max-w-2xl w-full">
    <div class="flex items-center gap-4">
        <flux:icon.cube/>
        <flux:heading size="">Nova Dimensão</flux:heading>
    </div>
    <form wire:submit='save' class="flex flex-col gap-4 mt-8">
        <div class="flex gap-4">
            <div class="w-8/10">
                <flux:input placeholder="Instrumento..."  wire:model='tituloInstrumento' readonly/>
            </div>
            <div class="w-2/10">
                <flux:input placeholder="Sequencia..." wire:model='sequencia' readonly/>  
            </div>  
        </div>

        <flux:input placeholder="Descrição..." wire:model='descricao'/>
        <flux:button type="submit" class="mt-4" variant="primary">Salvar</flux:button>
    </form>
</flux:modal>