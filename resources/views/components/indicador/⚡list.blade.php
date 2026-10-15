<?php

use Livewire\Component;
use App\Models\indicador;
use Flux\Flux;

new class extends Component
{
    
    protected $listeners = ['postInsert' => '$refresh'];

    public $dimensao_id;
    public $listOpen = [];

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

    public function abrir($id){
        if (!array_key_exists($id, $this->listOpen)){
            $this->listOpen[$id] = True;
        }
        else {
            $this->listOpen[$id] = !$this->listOpen[$id];
        }
    }
};
?>

<div>
    @foreach ($this->indicadores() as $ind)
        <flux:separator />

        <div class="flex items-center justify-between gap-2 py-2">
            <div class="flex items-center gap-2 ml-6 w-full h-full cursor-pointer" wire:click="abrir({{ $ind->id }})">
                <flux:icon.chart-bar class="size-5"/>
                @if (array_key_exists($ind->id, $this->listOpen))
                    @if ($this->listOpen[$ind->id])
                        <flux:text class="!font-bold">{{ $ind->sequencia.'. '.$ind->descricao  }}</flux:text>
                    @else    
                        <flux:text>{{ $ind->sequencia.'. '.$ind->descricao  }}</flux:text>
                    @endif
                @else
                    <flux:text>{{ $ind->sequencia.'. '.$ind->descricao  }}</flux:text>
                @endif
            </div>
            <flux:dropdown>   
                <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal"></flux:button>
                
                <flux:menu>
                    @if($ind->criterios->count() != 5)
                        <flux:modal.trigger name="add_criterio">
                            <flux:menu.item icon="plus-circle" wire:click='selectIndicador({{ $ind->id }})'>Adicionar Critério</flux:menu.item>
                        </flux:modal.trigger>
                    @else
                        <flux:menu.item icon="plus-circle" disabled wire:click='selectIndicador({{ $ind->id }})'>Adicionar Critério</flux:menu.item>
                    @endif

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

        @if (array_key_exists($ind->id, $this->listOpen))
            @if ($this->listOpen[$ind->id])
                <livewire:criterio.list :indicador_id="$ind->id"/>
            @endif
        @endif
    @endforeach
</div>