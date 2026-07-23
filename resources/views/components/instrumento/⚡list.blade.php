<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\instrumento;

new class extends Component
{
    use WithPagination;

    protected $listeners = ['postInsert' => '$refresh'];
    
    protected $search = '';
    #[On('search')]
    public function getSearch(string $s){
        $this->search = $s;

        $this->resetPage();
    }

    public function instrumentos(){
        return instrumento::where('titulo', 'ilike', '%'.$this->search.'%')->orderby('titulo', 'asc')->paginate(10);        
    }

    public function select(int $id){
        $this->dispatch('Detail', id : $id);
    }
};
?>


<div class="mt-8">
    <div class="grid grid-cols-2 gap-4 mb-4">
        @foreach ($this->instrumentos() as $inst)
        
        <flux:card class="flex items-center justify-between">
            <div class="flex items-start flex-col gap-2">
                <flux:heading size="lg">{{ $inst->titulo }}</flux:heading>
                <flux:text>{{ $inst->ano }} | 0 Avaliações Realizadas</flux:text>
            </div>
            <div class="flex gap-2">
                <flux:modal.trigger name="remove">
                    <flux:button icon="trash" wire:click='select({{ $inst->id }})'></flux:button>
                </flux:modal.trigger>
                
                <flux:modal.trigger name="edit">
                    <flux:button icon="pencil-square" wire:click='select({{ $inst->id }})'></flux:button>
                </flux:modal.trigger>

                <flux:modal.trigger name="details">
                    <flux:button icon='information-circle' wire:click='select({{ $inst->id }})'></flux:button>
                </flux:modal.trigger>
            </div>
        </flux:card>
            
        @endforeach
    </div>
    

    <flux:pagination :paginator="$this->instrumentos()" />
</div>