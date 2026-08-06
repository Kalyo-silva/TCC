<?php

use Livewire\Component;
use App\Models\criterio;
use App\Models\indicador;
use Livewire\Attributes\On;
use Flux\Flux;

new class extends Component
{
    public $indicador_id;
    public $descricaoindicador;
    public $descricao;
    public $sequencia;

    #[On('DetailIndicador')]
    public function getIdindicador($id){
        $this->indicador_id = $id;

        $indicador = indicador::find($this->indicador_id);

        if ($indicador){
            $this->descricaoindicador = $indicador->descricao;
        
            $this->sequencia = criterio::where('indicador_id', $this->indicador_id)->max('sequencia')+1;
        }
    }
    
    public function save(){
        $criterio = new criterio();


        $criterio->indicador_id = $this->indicador_id;
        $criterio->descricao = $this->descricao;
        $criterio->sequencia = $this->sequencia;

        $validated = $this->validate([
            "descricao" => "required",
            "sequencia" => "required|integer",
            "indicador_id" => "required|integer",
            ]);

        if ($validated){
            try{
                if ($criterio->save()){
                    $this->dispatch('postInsert');
                    Flux::toast(variant : "success", text: 'Registro criado com sucesso!');
                    $criterio->descricao = '';
                    Flux::modal('add_criterio')->close();
                }
            }
            catch (Throwable $e){  
                Flux::toast(variant : "danger", heading: 'Falha ao criar o registro...', text : $e->getMessage());
            }
        }
    }
};
?>

<flux:modal name="add_criterio" class="max-w-2xl w-full">
    <div class="flex items-center gap-4">
        <flux:icon.list-bullet/>
        <flux:heading size="">Novo Critério</flux:heading>
    </div>
    <form wire:submit='save' class="flex flex-col gap-4 mt-8">
        <div class="flex gap-4">
            <div class="w-8/10">
                <flux:input placeholder="Indicador..."  wire:model='descricaoindicador' readonly/>
            </div>
            <div class="w-2/10">
                <flux:input placeholder="Sequencia..." wire:model='sequencia' readonly/>  
            </div>  
        </div>

        <flux:textarea placeholder="Descrição..." wire:model='descricao'/>
        <flux:button type="submit" class="mt-4" variant="primary">Salvar</flux:button>
    </form>
</flux:modal>