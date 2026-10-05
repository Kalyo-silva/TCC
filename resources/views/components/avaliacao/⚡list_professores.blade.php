<?php

use Livewire\Component;
use Flux\Flux;
use App\Models\avaliacao_corpodocente;
use Livewire\Attributes\On;

new class extends Component
{
    public $avaliacao_id;

    public $professores;
    public $coordenador;

    public function mount(){
        $this->coordenador = avaliacao_corpodocente::with('curso')->with('professor')->where('avaliacao_id', $this->avaliacao_id)->where('coordenador', 1)->first();
        $this->professores = avaliacao_corpodocente::with('curso')->with('professor')->where('avaliacao_id', $this->avaliacao_id)->where('coordenador', 0)->get();
    }
};
?>

<flux:modal name="view_professores" class="max-w-4xl flex flex-col gap-4">
    @if ($this->coordenador)
        <div class="flex items-center gap-4">
            <flux:icon.user-plus/>
            <flux:heading size="">Histórico de corpo Docente - {{$this->coordenador->curso->nome}}</flux:heading>
        </div>

        <div class="flex flex-col gap-2">
            <flux:text>Corpo docente</flux:text>
            <div>
                <flux:card class="flex gap-8 items-center justify-between"> 
                    <div class="flex gap-2 items-center">   
                        <div class="flex flex-col gap-1">
                            {{ $this->coordenador->professor->first()->nome }}
                            <flux:badge size='sm' class="w-fit">Coordenador do curso</flux:badge>
                        </div>
                    </div>
                    <flux:dropdown>   
                        <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" class="cursor-pointer"></flux:button>
                        
                        <flux:menu>
                                <flux:menu.item icon="magnifying-glass" wire:click=''>Visualizar</flux:menu.item>
                        </flux:menu>
                    </flux:dropdown>
                </flux:card>
            </div>
        </div>
        <div class="gap-4 grid grid-cols-2">
            @foreach ($this->professores as $prof)
                <flux:card class="flex gap-8 items-center justify-between"> 
                    <div class="flex gap-2 items-center">   
                        <div class="flex flex-col gap-1">
                            {{ $prof->professor->first()->nome }}
                            <flux:badge size='sm' class="w-fit">{{$prof->professor->first()->titulacao}}</flux:badge>
                        </div>
                    </div>
                    <flux:dropdown>   
                        <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" class="cursor-pointer"></flux:button>
                        
                        <flux:menu>
                            <flux:menu.item icon="magnifying-glass" wire:click=''>Visualizar</flux:menu.item>
                        </flux:menu>
                    </flux:dropdown>
                </flux:card>
            @endforeach
        </div>
    @endif
</flux:modal>