<?php

use Livewire\Component;
use App\Models\dimensao;
use App\Models\indicador;
use Livewire\Attributes\On;
use Flux\Flux;

new class extends Component
{
    public $dimensao_id;
    public $descricaoDimensao;
    public $descricao;
    public $sequencia;

    #[On('DetailDimensao')]
    public function getIdDimensao($id){
        $this->dimensao_id = $id;


        $dimensao = dimensao::find($this->dimensao_id);

        if ($dimensao){
            $this->descricaoDimensao = $dimensao->descricao;
        
            $this->sequencia = indicador::where('dimensao_id', $this->dimensao_id)->max('sequencia')+1;
        }
    }
    
    public function save(){
        $indicador = new indicador();


        $indicador->dimensao_id = $this->dimensao_id;
        $indicador->descricao = $this->descricao;
        $indicador->sequencia = $this->sequencia;

        $validated = $this->validate([
            "descricao" => "required",
            "sequencia" => "required|integer",
            "dimensao_id" => "required|integer",
            ]);

        if ($validated){
            try{
                if ($indicador->save()){
                    $this->dispatch('postInsert');
                    Flux::toast(variant : "success", text: 'Registro criado com sucesso!');
                    Flux::modal('add_indicador')->close();
                }
            }
            catch (Throwable $e){  
                Flux::toast(variant : "danger", heading: 'Falha ao criar o registro...', text : $e->getMessage());
            }
        }
    }
};
?>

<flux:modal name="add_indicador" class="max-w-2xl w-full">
    <div class="flex items-center gap-4">
        <flux:icon.chart-bar/>
        <flux:heading size="">Novo Indicador</flux:heading>
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