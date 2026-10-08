<?php

use Livewire\Component;
use App\Models\curso;
use App\Models\mantenedor;
use App\Models\instituicao;
use App\Models\avaliacao;

new class extends Component
{
    public $curso_id;
    public $mantenedor_id;
    public $instituicao_id;

    public $avaliacao_a_id;
    public $avaliacao_a;

    public $avaliacao_b_id;
    public $avaliacao_b;

    public $mantenedores;
    public $instituicoes;
    public $cursos;
    public $avaliacoes;

    public function mount(){
        $this->mantenedores = mantenedor::get();
    }

    public function selectCurso($id){
        $this->curso_id = $id;

        $this->avaliacoes = avaliacao::where('curso_id', $this->curso_id)->orderBy('created_at')->get();

        $this->avaliacao_a_id = null;
        $this->avaliacao_a = null;
        $this->avaliacao_b_id = null;
        $this->avaliacao_b = null;
    }
    public function selectMantenedor($id){
        $this->mantenedor_id = $id;
        
        $this->cursos = Null;
        $this->curso_id = null;
        $this->avaliacao_a_id = null;
        $this->avaliacao_a = null;
        $this->avaliacao_b_id = null;
        $this->avaliacao_b = null;
        $this->avaliacoes = null;
        $this->instituicao_id = null; 
        $this->instituicoes = instituicao::where('mantenedor_id', $this->mantenedor_id)->get();
    }
    
    public function selectInstituicao($id){
        $this->instituicao_id = $id;

        $this->cursos = curso::where('instituicao_id', $this->instituicao_id)->get();
        $this->avaliacao_a_id = null;
        $this->avaliacao_a = null;
        $this->avaliacao_b_id = null;
        $this->avaliacao_b = null;
    }

    public function selectAvaliacao($id, $tipo){
        if ($tipo == 'A'){
            $this->avaliacao_a_id = $id;
            $this->avaliacao_a = avaliacao::find($id);

            $this->avaliacao_b = null;
        }
        else{
            $this->avaliacao_b_id = $id;
            $this->avaliacao_b = avaliacao::find($id);
        }
    }
};
?>

<div class="flex flex-col gap-4 h-full">
    @if ($this->mantenedores)
        <div class="flex gap-4 items-center">
            <div class="flex flex-col gap-2 w-full">
                <flux:heading>Mantenedor</flux:heading>
                <div class="overflow-hidden overflow-x-scroll pb-2">    
                    <flux:button.group>
                        @foreach ($this->mantenedores as $mantenedor)
                            @if ($this->mantenedor_id == $mantenedor->id)
                                <flux:button variant="primary" color="blue" wire:click="selectMantenedor({{ $mantenedor->id }})" >{{$mantenedor->nome}}</flux:button>
                            @else
                                <flux:button variant="outline" wire:click="selectMantenedor({{ $mantenedor->id }})" >{{$mantenedor->nome}}</flux:button>
                            @endif
                        @endforeach
                    </flux:button.group>
                </div>
            </div>
            @if ($this->instituicoes)
                <div class="flex flex-col gap-2 w-full">
                    <flux:heading>Instituições</flux:heading>
                    @if ($this->instituicoes->count() != 0)
                        <div class="overflow-hidden overflow-x-scroll pb-2">    
                                <flux:button.group>
                                @foreach ($this->instituicoes as $instituicao)
                                    @if ($this->instituicao_id == $instituicao->id)
                                        <flux:button variant="primary" color="blue" wire:click="selectInstituicao({{ $instituicao->id }})" >{{$instituicao->nome}}</flux:button>
                                    @else
                                        <flux:button variant="outline" wire:click="selectInstituicao({{ $instituicao->id }})" >{{$instituicao->nome}}</flux:button>
                                    @endif
                                @endforeach
                            </flux:button.group>
                        </div>
                    @else
                        <a href="{{route('instituicao')}}"><flux:button icon="plus">Cadastrar uma instituição</flux:button></a>
                    @endif
                </div>
            @endif
        </div>
    @else
        <div class="w-full h-full flex items-center justify-center">
            <flux:card class="min-w-1/2  flex flex-col gap-4">
                <div class="flex items-center gap-2">
                    <flux:icon.information-circle class="size-8"/>
                    <flux:heading size="xl">Primeira vez por aqui?</flux:heading> 
                </div>

                <flux:text size="lg">Ainda não temos informações o suficiente para demonstrar um comparativo, então por que resolvemos isso?</flux:text>
                
                <div class="grid grid-cols-3 gap-4 h-full">
                    <flux:card class="flex flex-col gap-2 px-4 py-2">
                        <flux:heading>Institucional</flux:heading> 
                        <a href={{ route('mantenedor') }}><flux:button class="w-full" icon="building-library">Cadastre Mantenedores</flux:button></a>
                        <a href={{ route('instituicao') }}><flux:button class="w-full" icon="academic-cap">Inclua instituições</flux:button></a>
                        <a href={{ route('professor') }}><flux:button class="w-full" icon="user-group">Adicione professores</flux:button></a>
                        <a href={{ route('curso') }}><flux:button class="w-full" icon="book-open">Crie cursos</flux:button></a>
                    </flux:card>
                    <flux:card class="flex flex-col gap-2 px-4 py-2">
                        <flux:heading>Avaliativo</flux:heading> 
                        <a href={{ route('instrumento') }}><flux:button class="w-full" icon="clipboard-document-list">Crie instrumentos de avaliação</flux:button></a>
                        <a href={{ route('avaliacao') }}><flux:button class="w-full" icon="trophy">Realize avaliações</flux:button></a>

                    </flux:card>
                    <flux:card class="flex flex-col gap-2 px-4 py-2">
                        <flux:heading icon="building-library">Evidências</flux:heading></a>
                        <a href={{ route('evidencia') }}><flux:button class="w-full" icon="paper-clip">Gerencie evidências</flux:button></a>

                    </flux:card>
                </div>
            </flux:card>
        </div>
    @endif

    @if ($this->cursos)
        <div class="flex flex-col gap-2 w-full">
            <flux:heading>Cursos</flux:heading>
            <div class="overflow-hidden overflow-x-scroll pb-2">    
                @if ($this->cursos->count() != 0)
                    <flux:button.group>
                        @foreach ($this->cursos as $curso)
                            @if ($this->curso_id == $curso->id)
                                <flux:button variant="primary" color="blue" wire:click="selectCurso({{ $curso->id }})" >{{$curso->nome}}</flux:button>
                            @else
                                <flux:button variant="outline" wire:click="selectCurso({{ $curso->id }})" >{{$curso->nome}}</flux:button>
                            @endif
                        @endforeach
                    </flux:button.group>
                @else
                    <a href="{{route('curso')}}"><flux:button icon="plus">Cadastrar um curso</flux:button></a>
                @endif
            </div>
        </div>
    @endif
    
    @if ($this->avaliacoes)
        <div class="flex flex-col gap-2 w-full">
            <flux:heading>Comparar</flux:heading>
            <div class="flex gap-4 w-full">
            @if ($this->avaliacoes->count() != 0)
                    <flux:card class="w-1/2">
                        <div class="overflow-hidden overflow-x-scroll pb-2">    
                            <flux:button.group>
                                @foreach ($this->avaliacoes as $avaliacao)
                                    @if ($this->avaliacao_a_id == $avaliacao->id)
                                        <flux:button variant="primary" color="blue" wire:click="selectAvaliacao({{$avaliacao->id}}, 'A')">{{$avaliacao->descricao}}</flux:button>
                                    @else
                                        <flux:button variant="outline" wire:click="selectAvaliacao({{$avaliacao->id}}, 'A')">{{$avaliacao->descricao}}</flux:button>
                                    @endif
                                @endforeach
                            </flux:button.group>

                            @if ($this->avaliacao_a)
                                <div class="flex px-4 py-2 gap-2 mt-4 items-center">
                                    <flux:icon.trophy />
                                    <div class="flex flex-col">
                                        <flux:heading size="lg">{{$this->avaliacao_a->descricao}}</flux:heading>
                                        <div class="flex items-center gap-2">
                                            <flux:text size="sm">Avaliação</flux:text>
                                            <flux:badge size="sm">{{$this->avaliacao_a->ano}}</flux:badge>
                                        </div>
                                    </div>
                                </div>
                                <livewire:dashboard.results :id="$this->avaliacao_a_id" :key="'avaliacao-a-'.$this->avaliacao_a_id"/>
                            @endif
                        </div>
                    </flux:card>
                    <flux:card class="w-1/2">
                        <div class="overflow-hidden overflow-x-scroll pb-2">    
                            <flux:button.group>
                                @foreach ($this->avaliacoes as $avaliacao)
                                    @if ($avaliacao->id != $this->avaliacao_a_id)
                                        @if ($this->avaliacao_b_id == $avaliacao->id)
                                            <flux:button variant="primary" color="blue" wire:click="selectAvaliacao({{$avaliacao->id}}, 'B')">{{$avaliacao->descricao}}</flux:button>
                                        @else
                                            <flux:button variant="outline" wire:click="selectAvaliacao({{$avaliacao->id}}, 'B')">{{$avaliacao->descricao}}</flux:button>
                                        @endif
                                    @endif
                                @endforeach
                            </flux:button.group>

                            @if ($this->avaliacao_b)
                                <div class="flex px-4 py-2 gap-2 mt-4 items-center">
                                    <flux:icon.trophy />
                                    <div class="flex flex-col">
                                        <flux:heading size="lg">{{$this->avaliacao_b->descricao}}</flux:heading>
                                        <div class="flex items-center gap-2">
                                            <flux:text size="sm">Avaliação</flux:text>
                                            <flux:badge size="sm">{{$this->avaliacao_b->ano}}</flux:badge>
                                        </div>
                                    </div>
                                </div>
                                <livewire:dashboard.results :id="$this->avaliacao_b_id" :key="'avaliacao-b-'.$this->avaliacao_b_id"/>
                            @endif
                        </div>
                    </flux:card>
                </div>
            @else
                <a href="{{route('avaliacao')}}"><flux:button icon="plus">Cadastrar uma nova Avaliação</flux:button></a>
            @endif
        </div>
    @endif
</div>