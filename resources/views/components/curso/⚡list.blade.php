<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

new class extends Component
{
    use WithPagination;

    public $sortBy = 'cursos.nome';
    public $sortDirection = 'asc';

    protected $listeners = ['postInsert' => '$refresh'];
    
    protected $search = '';
    #[On('search')]
    public function getSearch(string $s){
        $this->search = $s;

        $this->resetPage();
    }

    public function cursos(){
        return DB::table('cursos')->join('instituicoes', 'cursos.instituicao_id', '=', 'instituicoes.id')
                             ->select(
                                'cursos.id as id',
                                'cursos.nome as nome',
                                'instituicoes.nome as inst_nome'
                             )
                             ->where('cursos.nome', 'ilike', '%'.$this->search.'%')->orderby($this->sortBy, $this->sortDirection)->paginate(12);
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


    
{{-- 
<flux:table class="mt-8" :paginate="$this->cursos()">
    <flux:table.columns>
        <flux:table.column sortable :sorted="$sortBy === 'cursos.nome'" :direction="$sortDirection" wire:click="sort('cursos.nome')">Curso</flux:table.column>
        <flux:table.column sortable :sorted="$sortBy === 'instituicoes.nome'" :direction="$sortDirection" wire:click="sort('instituicoes.nome')">Instituição</flux:table.column>
    </flux:table.columns>

    <flux:table.rows>
        @foreach ($this->cursos() as $curso)
            <flux:table.row :key="$curso->id">
                <flux:table.cell class="flex items-center gap-3">{{ $curso->nome }}</flux:table.cell>

                <flux:table.cell class="whitespace-nowrap">{{ $curso->inst_nome}}</flux:table.cell>


                <flux:table.cell class="py-0">

                    <flux:dropdown>   
                        <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal"></flux:button>
                        
                        <flux:menu>
                            <flux:modal.trigger name="prof_add">
                                <flux:menu.item icon="user-plus" wire:click='select({{ $curso->id }})'>Vincular Professores</flux:menu.item>
                            </flux:modal.trigger>
                            <flux:modal.trigger name="edit">
                                <flux:menu.item icon="pencil-square" wire:click='select({{ $curso->id }})'>Editar</flux:menu.item>
                            </flux:modal.trigger>
                            <flux:modal.trigger name="remove">
                                <flux:menu.item variant="danger" icon="trash" wire:click='select({{ $curso->id }})'>Delete</flux:menu.item>
                            </flux:modal.trigger>
                        </flux:menu>

                    </flux:dropdown>

                </flux:table.cell>
            </flux:table.row>
        @endforeach
    </flux:table.rows>
</flux:table> --}}