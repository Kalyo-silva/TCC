<?php

use Livewire\Component;
use App\Models\criterio;
use Flux\Flux;

new class extends Component
{
    
    protected $listeners = ['postInsert' => '$refresh'];

    public $indicador_id;

    public function criterios(){
        return criterio::where('indicador_id', $this->indicador_id)->orderBy('sequencia')->get();
    }

    public function selectCriterio($id){
        $this->dispatch('DetailCriterio', id : $id);
    }

    
    public function up($id){
        $criterio = criterio::findOrFail($id);

        $criterioAnt = criterio::where('indicador_id', $criterio->indicador_id)
                               ->where('sequencia', $criterio->sequencia-1)
                               ->first();

        if ($criterioAnt){
            $criterio->sequencia = $criterio->sequencia-1;
            $criterioAnt->sequencia = $criterioAnt->sequencia+1;

            if ($criterio->save()){
                if ($criterioAnt->save()){
                    $this->dispatch('postInsert');
                    Flux::toast(variant : "success", text: 'Registro movido com sucesso!');
                }
            }
        }
    }

    public function down($id){
        $criterio = criterio::findOrFail($id);

        $criterioAnt = criterio::where('indicador_id', $criterio->indicador_id)
                               ->where('sequencia', $criterio->sequencia+1)
                               ->first();

        if ($criterioAnt){
            $criterio->sequencia = $criterio->sequencia+1;
            $criterioAnt->sequencia = $criterioAnt->sequencia-1;

            if ($criterio->save()){
                if ($criterioAnt->save()){
                    $this->dispatch('postInsert');
                    Flux::toast(variant : "success", text: 'Registro movido com sucesso!');
                }
            }
        }

    }
};
?>

<div class="flex flex-col gap-2">
    @foreach ($this->criterios() as $cri)
        <flux:separator />

        <div class="flex items-start justify-between">
            <div class="flex items-start gap-2 ml-12">
                <flux:icon.list-bullet class="size-5"/>
                <flux:text>{{ $cri->sequencia.'. '.$cri->descricao  }}</flux:text>
            </div>
            <flux:dropdown>   
                <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal"></flux:button>
                
                <flux:menu>
                    <flux:menu.item icon="arrow-up" wire:click='up({{ $cri->id }})'>Para Cima</flux:menu.item>

                    <flux:menu.item icon="arrow-down" wire:click='down({{ $cri->id }})'>Para Baixo</flux:menu.item>

                    <flux:modal.trigger name="edit_criterio">
                        <flux:menu.item icon="pencil-square" wire:click='selectCriterio({{ $cri->id }})'>Editar</flux:menu.item>
                    </flux:modal.trigger>

                    <flux:modal.trigger name="remove_criterio">
                        <flux:menu.item variant="danger" icon="trash" wire:click='selectCriterio({{ $cri->id }})'>Remover</flux:menu.item>
                    </flux:modal.trigger>
                </flux:menu>

            </flux:dropdown>
        </div>
    @endforeach
    
    <livewire:criterio.remove />
    <livewire:criterio.edit />
</div>