<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\avaliacao;

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
            
            $this->usuarioNome     = $avaliacao->usuario->name     ;
            $this->tituloInstrumento = $avaliacao->instrumento->titulo;
            $this->nomeCurso = $avaliacao->curso->nome;
        }
    } 

    public function select(int $id){
        $this->dispatch('AvaliacaoDetail', id : $id);
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
            <flux:input label="Curso" wire:model='nomeCurso' readonly/>
            <flux:input label="Instrumento de Avaliação" wire:model='tituloInstrumento' readonly/>
            <div class="flex gap-4">
                <div class="w-5/10">
                    <flux:input label="Data Inicial" type='date' wire:model='data_inicio' readonly/>
                </div>
                <div class="w-5/10">
                    <flux:input label="Data Final" type='date' wire:model='data_fim' readonly/>
                </div>
            </div>
            <flux:input label="Usuário responsável" wire:model='usuarioNome' readonly/>

            <div class="flex flex-row-reverse gap-4">
                @if ($this->id)
                    <a href="{{ route('avaliacao.execute', ['id' => $this->id]) }}"> 
                        <flux:button type="submit" class="mt-4" icon="play" wire:click="select({{ $this->id }})">Executar</flux:button> 
                    </a>
                @endif
                <flux:modal.trigger name="edit"> 
                    <flux:button type="submit" class="mt-4" icon="pencil-square" wire:click="select({{ $this->id }})">Editar</flux:button> 
                </flux:modal.trigger>
                <flux:modal.trigger name="remove"> 
                    <flux:button type="submit" class="mt-4" icon="trash" wire:click='select({{ $this->id }})'>Remover</flux:button> 
                </flux:modal.trigger>
            </div>
        </div>
</flux:modal>
