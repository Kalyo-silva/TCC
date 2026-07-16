<?php



use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\mantenedor;

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

    public function mantenedores(){
        return mantenedor::where('nome', 'ilike', '%'.$this->search.'%')->orderby('nome', 'asc')->paginate(10);        
    }

    public function select(int $id){
        $this->dispatch('MantenedorDetail', id : $id);
    }
};
?>

<div class="mt-8">
    <div class="grid grid-cols-2 gap-4 mb-4">
        @foreach ($this->mantenedores() as $mant)
        
        <flux:card class="flex items-center justify-between">
            <div class="flex items-start flex-col gap-2">
                <flux:heading size="lg">{{ $mant->nome }}</flux:heading>
                <flux:text>0 Instituições Vinculadas</flux:text>
            </div>
            <div class="flex gap-2">
                <flux:modal.trigger name="remove">
                    <flux:button icon="trash" wire:click='select({{ $mant->id }})'></flux:button>
                </flux:modal.trigger>
                
                <flux:modal.trigger name="edit">
                    <flux:button icon="pencil-square" wire:click='select({{ $mant->id }})'></flux:button>
                </flux:modal.trigger>

                <flux:modal.trigger name="details">
                    <flux:button icon='information-circle' wire:click='select({{ $mant->id }})'></flux:button>
                </flux:modal.trigger>
            </div>
        </flux:card>
            
        @endforeach
    </div>
    

    <flux:pagination :paginator="$this->mantenedores()" />
</div>