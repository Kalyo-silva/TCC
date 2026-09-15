<?php

use Livewire\Component;
use Flux\Flux;
use App\Models\professor;
use App\Models\curso;
use App\Models\curso_professores;
use Livewire\Attributes\On;

new class extends Component
{
    protected $listeners = ['postInsert' => '$refresh'];

    public $curso_id;
    public $professor_id;
    public $curso;

    #[On('Detail')]
    public function getIdCurso($id){
        $this->curso_id = $id;

        $this->curso = curso_professores::where('curso_id', $this->curso_id)->get();
    }

    public function getProfs(){
        if ($this->curso){
            return professor::whereNotIn('id', $this->curso->pluck('professor_id'))->whereNotIn('id', [$this->getCoordenador()->id])->orderby('nome', 'asc')->get();
        }
    }
    
    public function getCoordenador(){
        if ($this->curso_id){
            return curso::find($this->curso_id)->coordenador;
        }
    }

    public function addProf(){
        $validated = $this->validate([
            "curso_id" => "required",
            "professor_id" => "integer"
        ]);

        $curso_prof = new curso_professores();

        $curso_prof->curso_id = $this->curso_id;
        $curso_prof->professor_id = $this->professor_id;

        if ($validated){
            try{
                if ($curso_prof->save()){
                    $this->dispatch('postInsert');
                    Flux::toast(variant : "success", text: 'Professor vinculado com sucesso!');
                }
            }
            catch (Throwable $e){
                if ($e->getCode() == 23505){ 
                    Flux::toast(variant : "danger", heading: 'Falha ao criar o registro...', text: "Este Professor já está vinculado ao curso.");
                }
                else{   
                    Flux::toast(variant : "danger", heading: 'Falha ao criar o registro...', text : $e->getMessage());
                }
            }
        }

        $this->curso = curso_professores::where('curso_id', $this->curso_id)->get();
    }

    public function remProf(int $id){
        $curso_prof = curso_professores::findOrFail($id);

        if ($curso_prof){
            try{
                if ($curso_prof->delete()){
                    Flux::toast(variant : "success", text: 'Professor desvinculado com sucesso!');
                }
            }
            catch (Throwable $e){
                Flux::toast(variant : "danger", heading: 'Falha ao remover o registro...', text : $e->getMessage());
            }
        }
    }
};
?>

<flux:modal name="prof_add" class="max-w-4xl flex flex-col gap-4">
    <div class="flex items-center gap-4">
        <flux:icon.user-plus/>
        <flux:heading size="">Gerenciar Corpo Docente</flux:heading>
    </div>

    @if ($this->curso)
        <div class="flex flex-col gap-2">
            <flux:text>Vincular Professores</flux:text>
            <flux:button.group>
                <flux:button icon='user' />
                <flux:select wire:model='professor_id'>
                    <flux:select.option value="dummy" selected>Professor...</flux:select.option>
                    @foreach ($this->getProfs() as $prof)
                        <flux:select.option value="{{ $prof->id }}">{{$prof->nome}}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:button icon='plus' wire:click='addProf()' class="cursor-pointer"/>
            </flux:button.group>
        </div>
    @endif

    <div class="flex flex-col gap-2">
        <flux:text>Corpo docente</flux:text>
        <div>
            @if ($cordenador = $this->getCoordenador())
                <flux:card class="flex gap-8 items-center justify-between"> 
                    <div class="flex gap-2 items-center">   
                        <div class="flex flex-col gap-1">
                            {{ $cordenador->nome }}
                            <flux:badge size='sm' class="w-fit">Coordenador do curso</flux:badge>
                        </div>
                    </div>
                    <flux:dropdown>   
                        <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" class="cursor-pointer"></flux:button>
                        
                        <flux:menu>
                                <flux:menu.item icon="magnifying-glass" wire:click=''>Visualizar</flux:menu.item>
                        </flux:menu>
                    </flux:dropdown>
                </flux:card>

            @endif
        </div>
    </div>
    <div class="gap-4 grid grid-cols-2">
        @if ($this->curso != null)
            @foreach ($this->curso as $curso)
                <flux:card class="flex gap-8 items-center justify-between"> 
                    <div class="flex gap-2 items-center">   
                        <div class="flex flex-col gap-1">
                            {{ $curso->professor->nome }}
                            <flux:badge size='sm' class="w-fit">{{$curso->professor->titulacao}}</flux:badge>
                        </div>
                    </div>
                    <flux:dropdown>   
                        <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" class="cursor-pointer"></flux:button>
                        
                        <flux:menu>
                            <flux:menu.item icon="magnifying-glass" wire:click=''>Visualizar</flux:menu.item>
                            <flux:menu.item icon="user-minus" wire:click='remProf({{ $curso->id }})'>Desvincular</flux:menu.item>
                        </flux:menu>
                    </flux:dropdown>
                </flux:card>
            @endforeach
        @endif
    </div>
</flux:modal>