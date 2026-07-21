<?php

use App\Models\professor;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

new class extends Component
{
    use WithPagination;

    public $sortBy = 'nome';
    public $sortDirection = 'asc';

    protected $listeners = ['postInsert' => '$refresh'];
    
    protected $search = '';
    #[On('search')]
    public function getSearch(string $s){
        $this->search = $s;

        $this->resetPage();
    }

    public function professores(){
        return professor::where('nome', 'ilike', '%'.$this->search.'%')->orderby($this->sortBy, $this->sortDirection)->paginate(10);
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

<flux:table class="mt-8" :paginate="$this->professores()">
    <flux:table.columns>
        <flux:table.column sortable :sorted="$sortBy === 'nome'" :direction="$sortDirection" wire:click="sort('nome')">Professor</flux:table.column>
        <flux:table.column sortable :sorted="$sortBy === 'data_admissao'" :direction="$sortDirection" wire:click="sort('data_admissao')">Data de Admissão</flux:table.column>
        <flux:table.column sortable :sorted="$sortBy === 'titulacao'" :direction="$sortDirection" wire:click="sort('titulacao')">Titulação</flux:table.column>
        <flux:table.column sortable :sorted="$sortBy === 'regime'" :direction="$sortDirection" wire:click="sort('regime')">Regime</flux:table.column>
        <flux:table.column sortable :sorted="$sortBy === 'vinculo'" :direction="$sortDirection" wire:click="sort('vinculo')">Vínculo</flux:table.column>
    </flux:table.columns>

    <flux:table.rows>
        @foreach ($this->professores() as $prof)
            <flux:table.row :key="$prof->id">
                <flux:table.cell class="flex items-center gap-3">{{ $prof->nome }}</flux:table.cell>

                <flux:table.cell class="whitespace-nowrap">{{ $prof->data_admissao}}</flux:table.cell>

                <flux:table.cell class="py-0">
                    <flux:badge size="sm">{{ $prof->titulacao}}</flux:badge>
                </flux:table.cell>

                <flux:table.cell variant="strong">{{ $prof->regime}}</flux:table.cell>
                
                <flux:table.cell variant="strong">{{ $prof->vinculo}}</flux:table.cell>
                
                <flux:table.cell class="py-0">

                    <flux:dropdown>   
                        <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal"></flux:button>
                        
                        <flux:menu>
                            <flux:modal.trigger name="edit">
                                <flux:menu.item icon="pencil-square" wire:click='select({{ $prof->id }})'>Editar</flux:menu.item>
                            </flux:modal.trigger>
                            <flux:menu.separator />
                            <flux:menu.item icon="link"><a href="{{ $prof->lattes }}" target="_blank">Curriculum Lattes</a></flux:menu.item>
                            <flux:menu.separator />
                            <flux:modal.trigger name="remove">
                                <flux:menu.item variant="danger" icon="trash" wire:click='select({{ $prof->id }})'>Delete</flux:menu.item>
                            </flux:modal.trigger>
                        </flux:menu>

                    </flux:dropdown>

                </flux:table.cell>
            </flux:table.row>
        @endforeach
    </flux:table.rows>
</flux:table>