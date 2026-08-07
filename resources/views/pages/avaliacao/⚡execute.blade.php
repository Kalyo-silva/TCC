<?php

use Livewire\Component;
use App\Models\avaliacao;

new class extends Component
{
    public $avaliacao;
    public $dimensao = 0;
    public $indicador = 0;

    public $lastDimensao = false;
    public $firstDimensao = true;
    
    public $lastIndicador = false;
    public $firstIndicador = false;

    public function mount($id){
        $this->avaliacao = avaliacao::findOrFail($id);
    }

    public function nextDimensao(){        
        if ($this->dimensao == $this->avaliacao->instrumento->dimensoes->count() -1) {
            $this->lastDimensao = true;
        }else{  
            $this->dimensao += 1;
            $this->indicador = 0;
            $this->firstDimensao = false;
        }
        
    }
    public function previousDimensao(){     
        if ($this->dimensao == 0) {
            $this->firstDimensao = true;
        }else{  
            $this->dimensao -= 1;
            $this->indicador = 0;
            $this->lastDimensao = false;
        }
    }

    public function nextIndicador(){        
        if ($this->indicador == $this->avaliacao->instrumento->dimensoes[$this->dimensao]->indicadores->count() -1) {
            $this->lastIndicador = true;
        }else{  
            $this->indicador += 1;
            $this->firstIndicador = false;
        }
        
    }
    public function previousIndicador(){     
        if ($this->indicador == 0) {
            $this->firstIndicador = true;
        }else{  
            $this->indicador -= 1;
            $this->lastIndicador = false;
        }
    }
};
?>

<div class="flex flex-col gap-8">
    <div class="flex gap-8 justify-between">
        <flux:card class="px-4 py-4 w-full flex gap-4 items-center"> 
            <flux:icon.trophy class="size-8"/>
            <div>
                <flux:heading size="lg">{{ $this->avaliacao->descricao }}</flux:heading>
                <flux:text>{{ $this->avaliacao->curso->nome }}</flux:text>
            </div>
        </flux:card>
        <flux:card class="px-4 py-4 w-full flex gap-4 items-center">
            <flux:icon.clipboard-document-list class="size-8"/>
            <div>
                <flux:heading size="lg">{{ $this->avaliacao->instrumento->titulo }}</flux:heading>
                <flux:text>{{ $this->avaliacao->instrumento->ano }}</flux:text>
            </div>
        </flux:card>
    </div>

    <flux:card class="px-4 py-4 w-full flex gap-2 items-center">
        <flux:icon.cube class="size-8"/>

        <div>
            <flux:heading>Dimensão  # {{ $this->avaliacao->instrumento->dimensoes[$this->dimensao]->sequencia }} </flux:heading>
            <flux:text>{{ $this->avaliacao->instrumento->dimensoes[$this->dimensao]->descricao }}</flux:text>
        </div>
    </flux:card>



    <div class="flex flex-col gap-2">
        <flux:heading>Indicador</flux:heading>
        <flux:card class="p-0 w-full flex flex-col overflow-hidden">
            <div class="flex gap-2 items-center p-4 w-full">
                <flux:icon.chart-bar class="size-8"/>
                <div>
                    <flux:heading>Indicador  # {{ $this->avaliacao->instrumento->dimensoes[$this->dimensao]->indicadores[$this->indicador]->sequencia }} </flux:heading>
                    <flux:text>{{ $this->avaliacao->instrumento->dimensoes[$this->dimensao]->indicadores[$this->indicador]->descricao }}</flux:text>
                </div>
            </div>
            @foreach ($this->avaliacao->instrumento->dimensoes[$this->dimensao]->indicadores[$this->indicador]->criterios as $crit)     
                <div class="flex gap-2 items-start p-4 w-full border-t border-zinc-600 pl-12 text-justify">
                    <flux:icon.list-bullet/>
                    <flux:text>{{$crit->sequencia . '. ' . $crit->descricao }}</flux:text>
                </div>
            @endforeach
        </flux:card>
    </div>

    <div class="flex flex-col gap-2">
        <div class="flex items-center justify-between">
            <flux:heading>Evidências Anexadas</flux:heading>
            <flux:button variant="ghost" icon="plus"></flux:button>
        </div>
        
        <flux:card>
        </flux:card>
    </div>
    <div class="flex gap-2 flex-row-reverse">
        <flux:button :disabled="$this->lastDimensao"   icon:trailing="chevron-double-right" wire:click='nextDimensao()' >Proxíma Dimensão</flux:button>
        <flux:button :disabled="$this->lastIndicador"  icon:trailing="arrow-right"          wire:click='nextIndicador()'>Proxímo</flux:button>
        <flux:button :disabled="$this->firstIndicador" icon="arrow-left"                    wire:click='previousIndicador()'>Anterior</flux:button>
        <flux:button :disabled="$this->firstDimensao"  icon="chevron-double-left"           wire:click='previousDimensao()'>Dimensão Anterior</flux:button>
    </div>
</div>