<?php

use Livewire\Component;
use App\Models\dimensao;
use Flux\Flux;

new class extends Component
{
    
    protected $listeners = ['postInsert' => '$refresh'];
    
    public $instrumento_id;

    public function dimensoes(){
        return dimensao::where('instrumento_id', $this->instrumento_id)->orderBy('sequencia')->get();
    }

    public function selectdimensao($id){
        $this->dispatch('DetailDimensao', id : $id);
    }

    public function up($id){
        $dimensao = dimensao::findOrFail($id);

        $dimensaoAnt = dimensao::where('instrumento_id', $dimensao->instrumento_id)
                               ->where('sequencia', $dimensao->sequencia-1)
                               ->first();

        if ($dimensaoAnt){
            $dimensao->sequencia = $dimensao->sequencia-1;
            $dimensaoAnt->sequencia = $dimensaoAnt->sequencia+1;

            if ($dimensao->save()){
                if ($dimensaoAnt->save()){
                    $this->dispatch('postInsert');
                    Flux::toast(variant : "success", text: 'Registro movido com sucesso!');
                }
            }
        }
    }

    public function down($id){
        $dimensao = dimensao::findOrFail($id);

        $dimensaoAnt = dimensao::where('instrumento_id', $dimensao->instrumento_id)
                               ->where('sequencia', $dimensao->sequencia+1)
                               ->first();

        if ($dimensaoAnt){
            $dimensao->sequencia = $dimensao->sequencia+1;
            $dimensaoAnt->sequencia = $dimensaoAnt->sequencia-1;

            if ($dimensao->save()){
                if ($dimensaoAnt->save()){
                    $this->dispatch('postInsert');
                    Flux::toast(variant : "success", text: 'Registro movido com sucesso!');
                }
            }
        }

    }
};
?>
<div class="w-full flex flex-col gap-4">
    @foreach ($this->dimensoes() as $dim)
        <flux:card class="px-4 py-2 flex flex-col gap-2">
            <div class="flex justify-between gap-4 items-center">  
                <div class="flex gap-2 item-center">
                    <flux:icon.cube class="size-5"/>
                    <flux:text>{{ $dim->sequencia }}. {{  $dim->descricao  }}</flux:text>
                </div>
                <flux:dropdown>   
                    <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal"></flux:button>
                    
                    <flux:menu>
                        <flux:modal.trigger name="add_indicador">
                            <flux:menu.item icon="plus-circle" wire:click='selectdimensao({{ $dim->id }})'>Adicionar Indicador</flux:menu.item>
                        </flux:modal.trigger>

                        <flux:menu.item icon="arrow-up" wire:click='up({{ $dim->id }})'>Para Cima</flux:menu.item>

                        <flux:menu.item icon="arrow-down" wire:click='down({{ $dim->id }})'>Para Baixo</flux:menu.item>

                        <flux:modal.trigger name="edit_dimensao">
                            <flux:menu.item icon="pencil-square" wire:click='selectdimensao({{ $dim->id }})'>Editar</flux:menu.item>
                        </flux:modal.trigger>

                        <flux:modal.trigger name="remove_dimensao">
                            <flux:menu.item variant="danger" icon="trash" wire:click='selectdimensao({{ $dim->id }})'>Delete</flux:menu.item>
                        </flux:modal.trigger>
                    </flux:menu>

                </flux:dropdown>
            </div>

            <livewire:indicador.list :dimensao_id="$dim->id"/>
        </flux:card>
    @endforeach

    <livewire:indicador.create />
    <livewire:dimensao.remove />
    <livewire:dimensao.edit />
</div>
