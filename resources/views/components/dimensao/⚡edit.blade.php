<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\models\dimensao;
use Flux\Flux;

new class extends Component
{
    public $id;
    public $descricao;
    public $sequencia;
    public $tituloInstrumento;

    protected $instrumento;


    #[On('DetailDimensao')]
    public function getIdDimensao($id){
        $this->id = $id;

        $dimensao = dimensao::with('instrumento')->find($this->id);

        if ($dimensao){
            $this->descricao = $dimensao->descricao;
            $this->sequencia = $dimensao->sequencia;
            $this->tituloInstrumento = $dimensao->instrumento->titulo;
        }
    }

    public function save(){
        $curso = dimensao::find($this->id);

        $curso->descricao = $this->descricao;

        $validated = $this->validate([
            "descricao" => "required"
            ]);

        if ($validated){
            try{
                if ($curso->save()){
                    $this->dispatch('postInsert');
                    Flux::toast(variant : "success", text: 'Registro alterado com sucesso!');
                    Flux::modal('edit_dimensao')->close();
                }
            }
            catch (Throwable $e){  
                Flux::toast(variant : "danger", heading: 'Falha ao alterar o registro...', text : $e->getMessage());
            }
        }
    }
};
?>

<flux:modal name="edit_dimensao" class="max-w-2xl w-full">
    <div class="flex items-center gap-4">
        <flux:icon.cube/>
        <flux:heading size="">Editar Dimensão</flux:heading>
    </div>
    <form wire:submit='save' class="flex flex-col gap-4 mt-8">
        <div class="flex gap-4">
            <div class="w-8/10">
                <flux:input placeholder="Instrumento..."  wire:model='tituloInstrumento' readonly/>
            </div>
            <div class="w-2/10">
                <flux:input placeholder="Sequencia..." wire:model='sequencia' readonly/>  
            </div>  
        </div>

        <flux:input placeholder="Descrição..." wire:model='descricao'/>
        <flux:button type="submit" class="mt-4" variant="primary">Salvar</flux:button>
    </form>
</flux:modal>