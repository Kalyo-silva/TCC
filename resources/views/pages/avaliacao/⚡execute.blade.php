<?php

use Livewire\Component;
use Livewire\Attributes\Reactive;
use App\Models\avaliacao;
use App\Models\avaliacao_indicador;
use Livewire\Attributes\On;

new class extends Component
{
    public $avaliacao;
    public $dimensao = 0;
    public $indicador = 0;

    public $lastDimensao = false;
    public $firstDimensao = true;
    
    public $lastIndicador = false;
    public $firstIndicador = true;

    public $naoAplica = false;
    public $nota;
    public $avaliacao_indicador_id;

    public function saveNota(){
        if ($this->nota > 5 && $this->nota != null){
            $this->nota = 5;
        } 
        else if ($this->nota < 1 && $this->nota != null){
            $this->nota = 1;
        }

        $avaind = avaliacao_indicador::find($this->avaliacao_indicador_id);

        if ($avaind){
            $avaind->nota = $this->nota;

            try{
                if ($avaind->save()){
                    Flux::toast(variant : "success", text: 'Nota atualizada com sucesso!');
                }
            }
            catch (Throwable $e){  
                Flux::toast(variant : "danger", heading: 'Falha ao criar o registro...', text : $e->getMessage());
            }
        }

        $this->cadastraAvalicaoIndicador($this->avaliacao->id, $this->avaliacao->instrumento->dimensoes[$this->dimensao]->indicadores[$this->indicador]->id);
    }

    public function updatedNaoAplica(){
        if ($this->naoAplica){
            $this->nota = null;
            $this->saveNota();
        } else{
            $this->nota = 0;
            $this->saveNota();
        }

        $this->cadastraAvalicaoIndicador($this->avaliacao->id, $this->avaliacao->instrumento->dimensoes[$this->dimensao]->indicadores[$this->indicador]->id);
    }

    #[Reactive]
    private $avaliacao_indicador;

    #[On('update_avaliacao_evidencia')]
    public function update_avaliacao_evidencia(){
        $this->cadastraAvalicaoIndicador($this->avaliacao->id, $this->avaliacao->instrumento->dimensoes[$this->dimensao]->indicadores[$this->indicador]->id);
    }

    public function mount($id){
        $this->avaliacao = avaliacao::findOrFail($id);

        $this->cadastraAvalicaoIndicador($this->avaliacao->id, $this->avaliacao->instrumento->dimensoes[$this->dimensao]->indicadores[$this->indicador]->id);
    }

    public function cadastraAvalicaoIndicador($avaliacao_id, $indicador_id){
         //Valida se existe o avaliacao_indicador na base
        $avaind = avaliacao_indicador::where('avaliacao_id', $avaliacao_id)->where('indicador_id', $indicador_id)->first();

        if (!$avaind){
            $avaind = new avaliacao_indicador();

            $avaind->avaliacao_id = $avaliacao_id;
            $avaind->indicador_id = $indicador_id;
            $avaind->nota = 0;

            try{
                if ($avaind->save()){
                    Flux::toast(variant : "success", text: 'Avaliação indicador criado com sucesso!');
                }
            }
            catch (Throwable $e){  
                Flux::toast(variant : "danger", heading: 'Falha ao criar o registro...', text : $e->getMessage());
            }
        }

        $this->avaliacao_indicador = $avaind;
        $this->nota = $avaind->nota;
        $this->avaliacao_indicador_id = $avaind->id;
        
        if ($this->nota === null){
            $this->naoAplica = True;
        } else{
            $this->naoAplica = false;
        }
    }

    public function nextDimensao(){
        if ($this->dimensao != $this->avaliacao->instrumento->dimensoes->count()-1)  {
            $this->dimensao += 1;
            $this->indicador = 0;
            $this->firstDimensao = false;

            $this->lastIndicador = false;
            $this->firstIndicador = true;
        }

        if ($this->dimensao == $this->avaliacao->instrumento->dimensoes->count()-1) {
            $this->lastDimensao = true;
        }

        $this->cadastraAvalicaoIndicador($this->avaliacao->id, $this->avaliacao->instrumento->dimensoes[$this->dimensao]->indicadores[$this->indicador]->id);
    }
    public function previousDimensao(){     
        if ($this->dimensao != 0){  
            $this->dimensao -= 1;
            $this->indicador = 0;
            $this->lastDimensao = false;

            $this->lastIndicador = false;
            $this->firstIndicador = true;
        }
        
        if ($this->dimensao == 0) {
            $this->firstDimensao = true;
        }

        $this->cadastraAvalicaoIndicador($this->avaliacao->id, $this->avaliacao->instrumento->dimensoes[$this->dimensao]->indicadores[$this->indicador]->id);
    }

    public function nextIndicador(){        
        if ($this->indicador != $this->avaliacao->instrumento->dimensoes[$this->dimensao]->indicadores->count() -1){
            $this->indicador += 1;
            $this->firstIndicador = false;
        }

        if ($this->indicador == $this->avaliacao->instrumento->dimensoes[$this->dimensao]->indicadores->count() -1) {
            $this->lastIndicador = true;
        }

        $this->cadastraAvalicaoIndicador($this->avaliacao->id, $this->avaliacao->instrumento->dimensoes[$this->dimensao]->indicadores[$this->indicador]->id);
    }
    public function previousIndicador(){     
        if ($this->indicador != 0){  
            $this->indicador -= 1;
            $this->lastIndicador = false;
        }

        if ($this->indicador == 0) {
            $this->firstIndicador = true;
        }

        $this->cadastraAvalicaoIndicador($this->avaliacao->id, $this->avaliacao->instrumento->dimensoes[$this->dimensao]->indicadores[$this->indicador]->id);
    }
};
?>

<div class="flex flex-col gap-8 2xl:px-12 2xl:py-8">
    <div class="flex gap-8 justify-between">
        <flux:card class="px-4 py-4 w-full flex gap-4 items-center"> 
            <flux:icon.trophy class="size-8"/>
            <div>
                <flux:heading size="lg">{{ $this->avaliacao->descricao }}</flux:heading>
                <flux:text >{{ $this->avaliacao->curso->nome }}</flux:text>
            </div>
        </flux:card>
        <flux:card class="px-4 py-2 w-full flex gap-4 items-center">
            <flux:icon.clipboard-document-list class="size-8"/>
            <div>
                <flux:heading size="lg">{{ $this->avaliacao->instrumento->titulo }}</flux:heading>
                <flux:text >{{ $this->avaliacao->instrumento->ano }}</flux:text>
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
    
    <div class="w-full flex flex-row-reverse items-stretch gap-8">
        <div class="w-1/8 flex flex-col gap-2 items-center">
            <flux:heading class="w-full h-8 flex items-center">Nota</flux:heading>
            <flux:input wire:model="nota" type="number" max=5 min=1 wire:change="saveNota()" :disabled="$this->naoAplica"/>
        </div>
        <div class="w-fit flex flex-col gap-2 items-center">
            <flux:heading class="w-full h-8 flex items-center">Situação</flux:heading>
            <flux:checkbox wire:model.live="naoAplica" class="w-full" label="Não se Aplica"/>
        </div>
    </div>

    <div class="w-full flex flex-col gap-2">
        <div class="flex items-center justify-between">
            <flux:heading>Evidências Anexadas</flux:heading>
            <flux:modal.trigger name='evidencia_list'>
                <flux:button icon="paper-clip" size="sm">Anexar</flux:button>
            </flux:modal.trigger>
        </div>
        
        <flux:card class="grid grid-cols-4 gap-4">
            @if ($this->avaliacao_indicador)
                @foreach ($this->avaliacao_indicador->evidencias as $evd)
                    <flux:tooltip content="{{ $evd->evidencia->file_name ? $evd->evidencia->file_name : ($evd->evidencia->link ? $evd->evidencia->link : 'Visualizar') }}">
                        <flux:card class="h-12 w-full flex justify-start items-center gap-2 px-4 py-2 cursor-pointer hover:border-2 overflow-hidden">
                            @if($evd->evidencia->tipo == 1)
                                <flux:icon.document class="size-6"/>
                            @elseif($evd->evidencia->tipo == 2)
                                <flux:icon.photo class="size-6"/>
                            @elseif($evd->evidencia->tipo == 3)
                                <flux:icon.video-camera class="size-6"/>
                            @elseif($evd->evidencia->tipo == 4)
                                <flux:icon.speaker-wave class="size-6"/>
                            @elseif($evd->evidencia->tipo == 5)
                                <flux:icon.book-open class="size-6"/>
                            @elseif($evd->evidencia->tipo == 6)
                                <flux:icon.paper-clip class="size-6"/>
                            @endif
                            <flux:heading class="underline truncate">{{$evd->evidencia->titulo}}</flux:heading>
                        </flux:card>
                    </flux:tooltip>
                @endforeach
            @endif
        </flux:card>
    </div>
    
    <div class="flex gap-2 flex-row-reverse">
        <flux:button size='sm' :disabled="$this->lastDimensao"   icon:trailing="chevron-double-right" wire:click='nextDimensao()' >Proxíma Dimensão</flux:button>
        <flux:button size='sm' :disabled="$this->lastIndicador"  icon:trailing="arrow-right"          wire:click='nextIndicador()'>Proxímo</flux:button>
        <flux:button size='sm' :disabled="$this->firstIndicador" icon="arrow-left"                    wire:click='previousIndicador()'>Anterior</flux:button>
        <flux:button size='sm' :disabled="$this->firstDimensao"  icon="chevron-double-left"           wire:click='previousDimensao()'>Dimensão Anterior</flux:button>
    </div>
    


    <livewire:evidencia.listmodal :avaliacao_id="$avaliacao->id" :indicador_id="$this->avaliacao->instrumento->dimensoes[$this->dimensao]->indicadores[$this->indicador]->id"/>
</div>