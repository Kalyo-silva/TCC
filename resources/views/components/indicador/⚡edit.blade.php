<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\indicador;
use Flux\Flux;

new class extends Component
{
    public $id;
    public $descricao;
    public $sequencia;
    public $descricaoDimensao;

    #[On('DetailIndicador')]
    public function getIdIndicador($id){
        $this->id = $id;

        $indicador = indicador::with('dimensao')->find($this->id);

        if ($indicador){
            $this->descricao = $indicador->descricao;
            $this->sequencia = $indicador->sequencia;
            $this->descricaoDimensao = $indicador->dimensao->descricao;
        }
    }

    public function save(){
        $indicador = indicador::find($this->id);

        $indicador->descricao = $this->descricao;

        $validated = $this->validate([
            "descricao" => "required"
            ]);

        if ($validated){
            try{
                if ($indicador->save()){
                    $this->dispatch('postInsert');
                    Flux::toast(variant : "success", text: 'Registro alterado com sucesso!');
                    Flux::modal('edit_indicador')->close();
                }
            }
            catch (Throwable $e){  
                Flux::toast(variant : "danger", heading: 'Falha ao alterar o registro...', text : $e->getMessage());
            }
        }
    }
};
?>

<flux:modal name="edit_indicador" class="max-w-2xl w-full">
    <div class="flex items-center gap-4">
        <flux:icon.chart-bar/>
        <flux:heading size="">Editar Indicador</flux:heading>
    </div>
    <form wire:submit='save' class="flex flex-col gap-4 mt-8">
        <div class="flex gap-4">
            <div class="w-8/10">
                <flux:input placeholder="Dimensão..."  wire:model='descricaoDimensao' readonly/>
            </div>
            <div class="w-2/10">
                <flux:input placeholder="Sequencia..." wire:model='sequencia' readonly/>  
            </div>  
        </div>

        <flux:input placeholder="Descrição..." wire:model='descricao'/>
        <flux:button type="submit" class="mt-4" variant="primary">Salvar</flux:button>
    </form>
</flux:modal>