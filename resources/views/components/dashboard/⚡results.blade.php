<?php

use Livewire\Component;
use App\Models\avaliacao_indicador;

new class extends Component
{
    public $avaliacao_indicador;
    public $selectedDim;

    public $id;
    public $medias = [];

    public $SomaGeral = 0;
    public $totalDimensoes = 0;

    public function mount(){
        $this->getMedias();
    }

    private function getMedias(){
        $this->avaliacao_indicador = avaliacao_indicador::with('indicador')->where('avaliacao_id', $this->id)->whereNot('nota', Null)->whereNot('nota', 0)->get();

        $somas = [];
        $qtd_indicadores = [];
        
        foreach ($this->avaliacao_indicador as $avaind) {
            if (!array_key_exists($avaind->indicador->dimensao->descricao, $somas)){
                $somas[$avaind->indicador->dimensao->descricao] = 0;
                $qtd_indicadores[$avaind->indicador->dimensao->descricao] = 0;
            }

            $somas[$avaind->indicador->dimensao->descricao] += $avaind->nota;
            $qtd_indicadores[$avaind->indicador->dimensao->descricao] += 1;
        }

        foreach ($somas as $dim => $soma) {
            $this->medias[$dim] = $soma / $qtd_indicadores[$dim];
            $this->SomaGeral += $soma / $qtd_indicadores[$dim];
            $this->totalDimensoes += 1;
        }
    }

    public function getIndicadoresByDimensao($dim){
        $result = [];

        foreach ($this->avaliacao_indicador as $avaind) {
            if ($avaind->indicador->dimensao->descricao == $dim){
                array_push($result, $avaind);
            }
        }

        return $result;
    }

    public function selectDim($dim){
        if ($this->selectedDim == $dim){
            $this->selectedDim = null;
        }
        else{
            $this->selectedDim = $dim;
        }

        $this->getMedias();
    }
};
?>

<div class="flex flex-col px-4 py-2 gap-2">
    <flux:heading class="">Resultados</flux:heading>
    <div class="h-full flex flex-col justify-between p-0">
        <div class="h-10/12 flex flex-col px-4 py-4 gap-2 overflow-y-scroll">    
            @foreach ($this->medias as $dim => $med)
                <flux:button.group>
                    @if ($this->selectedDim == $dim)
                        <flux:button class="w-10/12" variant="primary" color="blue" icon="cube" wire:click="selectDim('{{ $dim }}')">{{ $dim }}</flux:button>
                    @else
                        <flux:button class="w-10/12" icon="cube" wire:click="selectDim('{{ $dim }}')">{{ $dim }}</flux:button>
                    @endif
                    <div class="w-2/12">
                        <flux:input class="w-full font-bold" style="text-align: center;" value="{{ round($med,2) }}" disabled></flux:input>
                    </div>
                </flux:button.group>
                @if ($this->selectedDim == $dim)
                    @foreach ($this->getIndicadoresByDimensao($dim) as $ind)
                        <flux:button.group class="w-4/5">
                            <flux:button class="w-10/12 overflow-hidden" icon="chart-bar">{{ $ind->indicador->descricao }}</flux:button>
                            <div class="w-2/12">
                                <flux:input class="w-full" style="text-align: center;" value="{{ round($ind->nota,2) }}" disabled></flux:input>
                            </div>
                        </flux:button.group>
                    @endforeach
                @endif
            @endforeach
        </div>
        <div class="h-2/12 flex items-center px-4 py-2 justify-between">
            @if ($this->totalDimensoes != 0)
                <flux:heading class="text-lg">Media Final da Avaliação:</flux:heading>
                <flux:card class="px-3 py-1">
                    <flux:text class="text-lg font-bold text-center">{{ round($this->SomaGeral / $this->totalDimensoes, 2) }}</flux:text>
                </flux:card>
            @endif
        </div>
    </div>
</div>