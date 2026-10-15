<?php

use Livewire\Component;
use App\Models\dimensao;
use Flux\Flux;

new class extends Component
{
    
    protected $listeners = ['postInsert' => '$refresh'];
    
    public $instrumento_id;
    public $listOpen = [];

    public function dimensoes(){
        return dimensao::where('instrumento_id', $this->instrumento_id)->orderBy('sequencia')->get();
    }

    public function selectdimensao($id){
        $this->dispatch('DetailDimensao', id : $id);
    }

    public function selectdimensaoCreate($id){
        $this->dispatch('DetailDimensao', id: $id);

        Flux::modal('add_indicador')->show();
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

<div class="w-full flex flex-col gap-4">
    @foreach ($this->dimensoes() as $dim)
        <flux:card class="px-4 py-2">
            <div class="flex justify-between gap-4 items-center py-2">  
                <div class="flex gap-2 item-center w-full cursor-pointer" wire:click="abrir({{ $dim->id }})">
                    <flux:icon.cube class="size-5"/>
                    @if (array_key_exists($dim->id, $this->listOpen))
                        @if ($this->listOpen[$dim->id])
                            <flux:text class="!font-bold">{{ $dim->sequencia }}. {{  $dim->descricao  }}</flux:text>
                        @else    
                            <flux:text>{{ $dim->sequencia }}. {{  $dim->descricao  }}</flux:text>
                        @endif
                    @else
                        <flux:text>{{ $dim->sequencia }}. {{  $dim->descricao  }}</flux:text>
                    @endif
                </div>
                <flux:dropdown>   
                    <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal"></flux:button>
                    
                    <flux:menu>
                        <flux:menu.item icon="plus-circle" wire:click='selectdimensaoCreate({{ $dim->id }})'>Adicionar Indicador</flux:menu.item>
                        
                        <flux:menu.item icon="arrow-up" wire:click='up({{ $dim->id }})'>Para Cima</flux:menu.item>

                        <flux:menu.item icon="arrow-down" wire:click='down({{ $dim->id }})'>Para Baixo</flux:menu.item>

                        <flux:modal.trigger name="edit_dimensao">
                            <flux:menu.item icon="pencil-square" wire:click='selectdimensao({{ $dim->id }})'>Editar</flux:menu.item>
                        </flux:modal.trigger>

                        <flux:modal.trigger name="remove_dimensao">
                            <flux:menu.item variant="danger" icon="trash" wire:click='selectdimensao({{ $dim->id }})'>Remover</flux:menu.item>
                        </flux:modal.trigger>
                    </flux:menu>

                </flux:dropdown>
            </div>
            @if (array_key_exists($dim->id, $this->listOpen))
                @if ($this->listOpen[$dim->id])
                    <livewire:indicador.list :dimensao_id="$dim->id"/>
                @endif
            @endif
        </flux:card>
    @endforeach

    <livewire:dimensao.remove />
    <livewire:dimensao.edit />

    <livewire:indicador.create />
    <livewire:indicador.remove />
    <livewire:indicador.edit />

    <livewire:criterio.create />
    <livewire:criterio.remove />
    <livewire:criterio.edit />
</div>


