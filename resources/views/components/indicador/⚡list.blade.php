<?php

use Livewire\Component;
use App\Models\indicador;
use Flux\Flux;

new class extends Component
{
    
    protected $listeners = ['postInsert' => '$refresh'];

    public $dimensao_id;

    public function indicadores(){
        return indicador::where('dimensao_id', $this->dimensao_id)->orderBy('sequencia')->get();
    }

    public function selectIndicador($id){
        $this->dispatch('DetailIndicador', id : $id);
    }

    
    public function up($id){
        $indicador = indicador::findOrFail($id);

        $indicadorAnt = indicador::where('dimensao_id', $indicador->dimensao_id)
                               ->where('sequencia', $indicador->sequencia-1)
                               ->first();

        if ($indicadorAnt){
            $indicador->sequencia = $indicador->sequencia-1;
            $indicadorAnt->sequencia = $indicadorAnt->sequencia+1;

            if ($indicador->save()){
                if ($indicadorAnt->save()){
                    $this->dispatch('postInsert');
                    Flux::toast(variant : "success", text: 'Registro movido com sucesso!');
                }
            }
        }
    }

    public function down($id){
        $indicador = indicador::findOrFail($id);

        $indicadorAnt = indicador::where('dimensao_id', $indicador->dimensao_id)
                               ->where('sequencia', $indicador->sequencia+1)
                               ->first();

        if ($indicadorAnt){
            $indicador->sequencia = $indicador->sequencia+1;
            $indicadorAnt->sequencia = $indicadorAnt->sequencia-1;

            if ($indicador->save()){
                if ($indicadorAnt->save()){
                    $this->dispatch('postInsert');
                    Flux::toast(variant : "success", text: 'Registro movido com sucesso!');
                }
            }
        }

    }
};
?>

<div class="flex flex-col gap-2">

    @foreach ($this->indicadores() as $ind)
        <flux:separator />

        <div class="flex items-center justify-between gap-2">
            <div class="flex items-center gap-2 ml-6">
                <flux:icon.chart-bar class="size-5"/>
                <flux:text>{{ $ind->sequencia.'. '.$ind->descricao  }}</flux:text>
            </div>
            <flux:dropdown>   
                <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal"></flux:button>
                
                <flux:menu>
                    <flux:modal.trigger name="add_criterio">
                        <flux:menu.item icon="plus-circle" wire:click='selectIndicador({{ $ind->id }})'>Adicionar Critério</flux:menu.item>
                    </flux:modal.trigger>

                    <flux:menu.item icon="arrow-up" wire:click='up({{ $ind->id }})'>Para Cima</flux:menu.item>

                    <flux:menu.item icon="arrow-down" wire:click='down({{ $ind->id }})'>Para Baixo</flux:menu.item>

                    <flux:modal.trigger name="edit_indicador">
                        <flux:menu.item icon="pencil-square" wire:click='selectIndicador({{ $ind->id }})'>Editar</flux:menu.item>
                    </flux:modal.trigger>

                    <flux:modal.trigger name="remove_indicador">
                        <flux:menu.item variant="danger" icon="trash" wire:click='selectIndicador({{ $ind->id }})'>Remover</flux:menu.item>
                    </flux:modal.trigger>
                </flux:menu>

            </flux:dropdown>
        </div>

        <livewire:criterio.list :indicador_id="$ind->id"/>
    @endforeach

    <livewire:indicador.remove />
    <livewire:indicador.edit />
    <livewire:criterio.create />
</div>