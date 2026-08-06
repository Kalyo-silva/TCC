<?php

use Livewire\Component;
use App\Models\avaliacao;

new class extends Component
{
    public $avaliacao;
    public $dimensao = 0;
    public $indicador = 0;

    public function mount($id){
        $this->avaliacao = avaliacao::findOrFail($id);
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
                <flux:icon.chart-bar/>
                {{'# ' . $this->avaliacao->instrumento->dimensoes[$this->dimensao]->indicadores[$this->indicador]->sequencia . ' - ' . $this->avaliacao->instrumento->dimensoes[$this->dimensao]->indicadores[$this->indicador]->descricao }}
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
        <flux:button icon:trailing="arrow-right" tr>Proxímo</flux:button>
        <flux:button icon="arrow-left">Anterior</flux:button>
    </div>
</div>