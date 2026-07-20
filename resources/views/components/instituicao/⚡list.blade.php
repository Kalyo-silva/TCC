<?php

use Livewire\Component;
use App\models\instituicao;
use Livewire\WithPagination;
use Livewire\Attributes\On;

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

    public function instituicoes(){
        return instituicao::with('mantenedor')->where('nome', 'ilike', '%'.$this->search.'%')->orderby('nome', 'asc')->paginate(10);
    }

    public function select(int $id){
        $this->dispatch('Detail', id : $id);
    }
};
?>

<div>
    <div class="grid grid-cols-3 items-center gap-4 mt-8 mb-8">
        @foreach ($this->instituicoes() as $inst)
            <flux:modal.trigger name="details">
                <flux:card wire:click='' class="cursor-pointer" wire:click='select({{ $inst->id }})'>
                    <div class="flex items-start gap-4">
                        <img src="{{asset('storage/img_instituicoes/'.$inst->logo)}}" alt="pfp" class="size-21 rounded-lg">
                        <div class="flex flex-col">
                            <flux:heading size="lg">{{ $inst->nome }}</flux:heading>
                            <flux:text>{{ $inst->sigla }}</flux:text>
                            <div class="flex items-end gap-1 border rounded-lg px-2 py-1 border-zinc-600 mt-2">
                                <flux:icon.building-library class="size-5 text-zinc-300"/>
                                <flux:text class="truncate">{{ $inst->mantenedor->nome }}</flux:text>
                            </div>
                        </div>
                    </div>
                </flux:card>
            </flux:modal.trigger>
        @endforeach
    </div>
    
    <flux:pagination :paginator="$this->instituicoes()" />
</div>