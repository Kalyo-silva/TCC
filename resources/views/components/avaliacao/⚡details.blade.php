<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\avaliacao;
use App\Models\avaliacao_corpodocente;
use App\Models\avaliacao_indicador;

new class extends Component
{
    protected $listeners = ['postInsert' => '$refresh'];
    
    public $avaliacao;
    public $id;
    public $instrumento_id;
    public $curso_id;
    public $descricao;
    public $ano;
    public $data_inicio;
    public $data_fim;
    public $situacao;

    public $usuarioNome;
    public $tituloInstrumento;
    public $nomeCurso;

    public $avaliacao_indicador;

    public $medias = [];

    public $SomaGeral = 0;
    public $totalDimensoes = 0;

    #[On('AvaliacaoDetail')]
    public function getIdAvaliacao($id){
        $this->medias = [];

        $this->SomaGeral = 0;
        $this->totalDimensoes = 0;

        $this->id = $id;

        $this->avaliacao = avaliacao::find($this->id);

        if ($this->avaliacao){

            $this->instrumento_id = $this->avaliacao->instrumento_id ; 
            $this->curso_id       = $this->avaliacao->curso_id       ; 
            $this->descricao      = $this->avaliacao->descricao      ; 
            $this->ano            = $this->avaliacao->ano            ; 
            $this->data_inicio    = $this->avaliacao->data_inicio    ; 
            $this->data_fim       = $this->avaliacao->data_fim       ; 
            $this->situacao       = $this->avaliacao->situacao       ; 
            
            $this->usuarioNome     = $this->avaliacao->usuario->name     ;
            $this->tituloInstrumento = $this->avaliacao->instrumento->titulo;
            $this->nomeCurso = $this->avaliacao->curso->nome;
        }

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

    public function select(int $id){
        $this->dispatch('AvaliacaoDetail', id : $id);
    }


    public function executeAvaliacao(){
        if ($this->situacao == 0){

            $avaliacao = avaliacao::find($this->id);
            if ($avaliacao){
                $avaliacao->situacao = 1; 
            
                if ($avaliacao->save()){
                    $this->dispatch('postInsert');
                }
            }

            // Caso for iniciar a avaliação, cadastra o histórico do corpo docente
        
            $coordenador = avaliacao_corpodocente::where('avaliacao_id', $avaliacao->id)->where('professor_id', $avaliacao->curso->coordenador_id)->first();
            // Cadastra o cordenador primeiro
            if (!$coordenador) {
                
                $coordenador = new avaliacao_corpodocente();
                
                $coordenador->avaliacao_id = $avaliacao->id;
                $coordenador->curso_id = $avaliacao->curso->id;
                $coordenador->professor_id = $avaliacao->curso->coordenador_id;
                $coordenador->coordenador = 1;
                
                try{
                    $coordenador->save();
                }
                catch (Throwable $e){
                    if ($e->getCode() == 23505){ 
                        Flux::toast(variant : "danger", heading: 'Falha ao alterar o registro...', text: "Este nome já está cadastrado no sistema.");
                    }
                    else{   
                        Flux::toast(variant : "danger", heading: 'Falha ao alterar o registro...', text : $e->getMessage());
                    }
                }

                // Cadastra o restante do corpo docente
                foreach ($avaliacao->curso->professores as $prof) {
                    $professor = new avaliacao_corpodocente();
                    
                    $professor->avaliacao_id = $avaliacao->id;
                    $professor->curso_id = $avaliacao->curso_id;
                    $professor->professor_id = $prof->id;
                    $professor->coordenador = 0;
                    
                    try{
                        $professor->save();
                    }
                    catch (Throwable $e){
                        if ($e->getCode() == 23505){ 
                            Flux::toast(variant : "danger", heading: 'Falha ao alterar o registro...', text: "Este nome já está cadastrado no sistema.");
                        }
                        else{   
                            Flux::toast(variant : "danger", heading: 'Falha ao alterar o registro...', text : $e->getMessage());
                        }
                    }
                }
            }
        }

        return to_route('avaliacao.execute', ['id' => $this->id]);
    }
}
?>

<flux:modal name="details" class="min-w-3/4 2xl:min-w-1/2">
    <div class="flex items-center gap-4">
        <flux:icon.plus/>
        <flux:heading size="">Detalhes da Avaliação</flux:heading>
    </div>
    <div class="flex gap-4 w-full">
        <div class="flex flex-col gap-4 mt-4 w-4/6">
            <div class="flex gap-4">
                <div class="w-8/10">
                    <flux:input label="Descrição" wire:model='descricao' readonly/>
                </div>
                <div class="w-2/10">
                    <flux:input label="Ano" wire:model='ano' readonly/>
                </div>
            </div>

            <flux:input.group label="Curso">
                <flux:button icon='book-open'/>
                <flux:input wire:model='nomeCurso' readonly/>
            </flux:input.group>
            <flux:input.group label="Instrumento de Avaliação">
                <flux:button icon='clipboard-document-list'/>
                <flux:input wire:model='tituloInstrumento' readonly/>
            </flux:input.group>

            <div class="flex gap-4">
                <div class="w-5/10">
                    <flux:input label="Data Inicial" type='date' wire:model='data_inicio' readonly/>
                </div>
                <div class="w-5/10">
                    <flux:input label="Data Final" type='date' wire:model='data_fim' readonly/>
                </div>
            </div>
            <flux:input.group label="Usuário responsável">
                <flux:button icon='user'/>
                <flux:input wire:model='usuarioNome' readonly/>
            </flux:input.group>
        </div>
        <div class="flex flex-col mt-3 gap-2  w-2/6 overflow-hidden">
            <flux:heading class="">Resultados</flux:heading>
            <flux:card class="h-full flex flex-col justify-between p-0">
                <div class="h-10/12 flex flex-col px-4 py-4 gap-2 max-h-80 overflow-y-scroll">    
                    @foreach ($this->medias as $dim => $med)
                        <flux:button.group>
                            <flux:button class="w-10/12" icon="cube">{{ $dim }}</flux:button>
                            <div class="w-2/12">
                                <flux:input class="w-full font-bold" style="text-align: center;" value="{{ round($med,2) }}" disabled></flux:input>
                            </div>
                        </flux:button.group>
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
            </flux:card>
        </div>
    </div>
    <div class="grid {{ $this->situacao == 1 ? 'grid-cols-5' : 'grid-cols-4' }} gap-4">
        <flux:modal.trigger name="remove"> 
            <flux:button type="submit" class="mt-4" icon="trash" wire:click='select({{ $this->id }})'>Remover</flux:button> 
        </flux:modal.trigger>
        <flux:modal.trigger name="edit"> 
            <flux:button type="submit" class="mt-4" icon="pencil-square" wire:click="select({{ $this->id }})">Editar</flux:button> 
        </flux:modal.trigger>
        <flux:modal.trigger name="view_professores">
            <flux:button class="mt-4" icon="user" :disabled="$this->situacao == 0">Docentes</flux:button> 
        </flux:modal.trigger>
        @if ($this->id)
            @if ($this->situacao == 0)
                <flux:button type="submit" class="mt-4" icon="play" wire:click="executeAvaliacao()">Executar</flux:button> 
            @elseif ($this->situacao == 1)
                <flux:button type="submit" class="mt-4" icon="play" wire:click="executeAvaliacao()">Continuar</flux:button> 
                <flux:modal.trigger name="finish">
                    <flux:button type="submit" class="mt-4" icon="check-circle">Concluir</flux:button>
                </flux:modal.trigger> 
            @else
                <flux:tooltip content="Avaliação já concluida." class="w-full">
                    <flux:button class="mt-4 w-full" icon="eye" wire:click="executeAvaliacao()">Visualizar</flux:button> 
                </flux:tooltip>
            @endif
        @endif
    </div>

    <livewire:avaliacao.list_professores :avaliacao_id="$this->id"/>
    <livewire:avaliacao.finish :avaliacao_id="$this->id"/>
</flux:modal>
