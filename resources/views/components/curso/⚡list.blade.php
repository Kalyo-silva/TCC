<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\mantenedor;
use App\Models\instituicao;

new class extends Component
{
    use WithPagination;

    public $sortBy = 'cursos.nome';
    public $sortDirection = 'asc';

    public $instituicoes;
    public $mantenedores;
    public $mant_id = 0;
    public $inst_id = 0;

    protected $listeners = ['postInsert' => '$refresh'];
    
    protected $search = '';
    #[On('search')]
    public function getSearch(string $s){
        $this->search = $s;

        $this->resetPage();
    }

    public function mount(){
        $this->mantenedores = mantenedor::orderBy('nome', 'asc')->get();

    }

    public function getInstituicoes(){
        $this->instituicoes = Null;
        $this->inst_id = 0;
        
        if ($this->mant_id != 0) {
            $this->instituicoes = instituicao::where('mantenedor_id', $this->mant_id)->get();
        }

        $this->cursos();
    }

    public function cursos(){

        if ($this->inst_id != 0){
            return DB::table('cursos')->join('instituicoes', 'cursos.instituicao_id', '=', 'instituicoes.id')
                                ->select(
                                    'cursos.id as id',
                                    'cursos.nome as nome',
                                    'instituicoes.nome as inst_nome',
                                    'instituicoes.id as inst_id',
                                    'instituicoes.mantenedor_id as mant_id'
                                )
                                ->where('cursos.nome', 'ilike', '%'.$this->search.'%')
                                ->where('instituicoes.id', $this->inst_id)->orderby($this->sortBy, $this->sortDirection)->paginate(12);

        }
        elseif ($this->mant_id != 0){
            return DB::table('cursos')->join('instituicoes', 'cursos.instituicao_id', '=', 'instituicoes.id')
                                ->select(
                                    'cursos.id as id',
                                    'cursos.nome as nome',
                                    'instituicoes.nome as inst_nome',
                                    'instituicoes.id as inst_id',
                                    'instituicoes.mantenedor_id as mant_id'
                                )
                                ->where('cursos.nome', 'ilike', '%'.$this->search.'%')
                                ->where('instituicoes.mantenedor_id', $this->mant_id)->orderby($this->sortBy, $this->sortDirection)->paginate(12);

        }
        else {
            return DB::table('cursos')->join('instituicoes', 'cursos.instituicao_id', '=', 'instituicoes.id')
                                ->select(
                                    'cursos.id as id',
                                    'cursos.nome as nome',
                                    'instituicoes.nome as inst_nome',
                                    'instituicoes.id as inst_id',
                                    'instituicoes.mantenedor_id as mant_id'
                                )
                                ->where('cursos.nome', 'ilike', '%'.$this->search.'%')->orderby($this->sortBy, $this->sortDirection)->paginate(12);
        }
    }

    public function sort($column) {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }
    
    public function select(int $id){
        $this->dispatch('Detail', id : $id);
    }
};
?>

<div>
    <div class="flex flex-row-reverse mt-8">
        <div class="flex gap-4 items-center w-1/2">
            <flux:button.group class="w-1/2">
                <flux:button icon="building-library"></flux:button>
                <flux:select wire:model="mant_id" wire:change="getInstituicoes()">
                    <flux:select.option value="0">Selecione...</flux:select.option>
                    @foreach ($this->mantenedores as $mant)
                        <flux:select.option value="{{$mant->id}}">{{ $mant->nome }}</flux:select.option>
                    @endforeach
                </flux:select>
            </flux:button.group>
            <flux:button.group class="w-1/2">
                <flux:button icon="academic-cap"></flux:button>
                <flux:select wire:model="inst_id" wire:change="cursos()">
                    <flux:select.option value="0">Selecione...</flux:select.option>
                    @if ($this->instituicoes)
                        @foreach ($this->instituicoes as $inst)
                            <flux:select.option value="{{$inst->id}}">{{ $inst->nome }}</flux:select.option>
                        @endforeach
                    @endif
                </flux:select>
            </flux:button.group >
        </div>
    </div>
    <div class="grid grid-cols-3 mb-8 mt-8 2xl:grid-cols-4 gap-4">
        @foreach ($this->cursos() as $curso)
            <flux:card class="flex justify-between">
                <div class="flex flex-col">
                    <flux:heading>{{$curso->nome}}</flux:heading>
                    <flux:text>{{$curso->inst_nome}}</flux:text>
                </div>
                    <flux:dropdown>   
                        <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal"></flux:button>
                        
                        <flux:menu>
                            <flux:modal.trigger name="prof_add">
                                <flux:menu.item icon="user-plus" wire:click='select({{ $curso->id }})'>Gerenciar Corpo Docente</flux:menu.item>
                            </flux:modal.trigger>
                            <flux:modal.trigger name="edit">
                                <flux:menu.item icon="pencil-square" wire:click='select({{ $curso->id }})'>Editar</flux:menu.item>
                            </flux:modal.trigger>
                            <flux:modal.trigger name="remove">
                                <flux:menu.item variant="danger" icon="trash" wire:click='select({{ $curso->id }})'>Delete</flux:menu.item>
                            </flux:modal.trigger>
                        </flux:menu>
                    </flux:dropdown>
            </flux:card>
        @endforeach
    </div>

    <flux:pagination :paginator="$this->cursos()" />
</div>
