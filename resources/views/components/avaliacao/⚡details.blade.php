<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\avaliacao;
use App\Models\avaliacao_corpodocente;

new class extends Component
{
    protected $listeners = ['postInsert' => '$refresh'];
    
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

    #[On('AvaliacaoDetail')]
    public function getIdAvaliacao($id){
        $this->id = $id;

        $avaliacao = avaliacao::find($this->id);

        if ($avaliacao){

            $this->instrumento_id = $avaliacao->instrumento_id ; 
            $this->curso_id       = $avaliacao->curso_id       ; 
            $this->descricao      = $avaliacao->descricao      ; 
            $this->ano            = $avaliacao->ano            ; 
            $this->data_inicio    = $avaliacao->data_inicio    ; 
            $this->data_fim       = $avaliacao->data_fim       ; 
            $this->situacao       = $avaliacao->situacao       ; 
            
            $this->usuarioNome     = $avaliacao->usuario->name     ;
            $this->tituloInstrumento = $avaliacao->instrumento->titulo;
            $this->nomeCurso = $avaliacao->curso->nome;
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
        
            // Cadastra o cordenador primeiro
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

        return to_route('avaliacao.execute', ['id' => $this->id]);
    }
}
?>

<flux:modal name="details">
    <div class="flex items-center gap-4">
        <flux:icon.plus/>
        <flux:heading size="">Detalhes da Avaliação</flux:heading>
    </div>
        <div class="flex flex-col gap-4 mt-4">
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

            <div class="grid grid-cols-4  gap-4">
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
                    @else
                        <flux:tooltip content="Avaliação já concluida.">
                            <flux:button class="mt-4" icon="eye" wire:click="executeAvaliacao()">Visualizar</flux:button> 
                        </flux:tooltip>
                    @endif
                @endif
            </div>
        </div>

        <livewire:avaliacao.list_professores :avaliacao_id="$this->id"/>
</flux:modal>
