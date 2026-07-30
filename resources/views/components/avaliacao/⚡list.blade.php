<?php



use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\avaliacao;

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

    public function avaliacoes(){
        return avaliacao::where('descricao', 'ilike', '%'.$this->search.'%')->orderby('descricao')->orderBy('ano')->paginate(10);        
    }

    public function select(int $id){
        $this->dispatch('AvaliacaoDetail', id : $id);
    }
};
?>

<div class="mt-8">
    <div class="grid grid-cols-2 gap-4 mb-4">
        @foreach ($this->avaliacoes() as $ava)
        
        <flux:card class="flex items-center justify-between">
            <div class="flex items-start flex-col gap-2">
                <flux:heading size="lg">{{ $ava->descricao }}</flux:heading>
                <flux:text size="lg">{{ $ava->curso->nome }}</flux:text>
            </div>
            <div class="flex gap-2">
                <flux:modal.trigger name="remove">
                    <flux:button icon="trash" wire:click='select({{ $ava->id }})'></flux:button>
                </flux:modal.trigger>
                
                <flux:modal.trigger name="edit">
                    <flux:button icon="pencil-square" wire:click='select({{ $ava->id }})'></flux:button>
                </flux:modal.trigger>

                <flux:modal.trigger name="details">
                    <flux:button icon='information-circle' wire:click='select({{ $ava->id }})'></flux:button>
                </flux:modal.trigger>
            </div>
        </flux:card>
            
        @endforeach
    </div>

    <flux:pagination :paginator="$this->avaliacoes()" />
</div>