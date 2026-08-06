<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\criterio;
use Flux\Flux;

new class extends Component
{
    public $id;
    public $descricao;
    public $sequencia;
    public $descricaoIndicador;

    #[On('DetailCriterio')]
    public function getIdCriterio($id){
        $this->id = $id;

        $criterio = criterio::with('indicador')->find($this->id);

        if ($criterio){
            $this->descricao = $criterio->descricao;
            $this->sequencia = $criterio->sequencia;
            $this->descricaoIndicador = $criterio->indicador->descricao;
        }
    }

    public function save(){
        $criterio = criterio::find($this->id);

        $criterio->descricao = $this->descricao;

        $validated = $this->validate([
            "descricao" => "required"
            ]);

        if ($validated){
            try{
                if ($criterio->save()){
                    $this->dispatch('postInsert');
                    Flux::toast(variant : "success", text: 'Registro alterado com sucesso!');
                    Flux::modal('edit_criterio')->close();
                }
            }
            catch (Throwable $e){  
                Flux::toast(variant : "danger", heading: 'Falha ao alterar o registro...', text : $e->getMessage());
            }
        }
    }
};
?>

<flux:modal name="edit_criterio" class="max-w-2xl w-full">
    <div class="flex items-center gap-4">
        <flux:icon.list-bullet/>
        <flux:heading size="">Editar Critério</flux:heading>
    </div>
    <form wire:submit='save' class="flex flex-col gap-4 mt-8">
        <div class="flex gap-4">
            <div class="w-8/10">
                <flux:input placeholder="Indicador..."  wire:model='descricaoIndicador' readonly/>
            </div>
            <div class="w-2/10">
                <flux:input placeholder="Sequencia..." wire:model='sequencia' readonly/>  
            </div>  
        </div>

        <flux:textarea placeholder="Descrição..." wire:model='descricao'/>
        <flux:button type="submit" class="mt-4" variant="primary">Salvar</flux:button>
    </form>
</flux:modal>