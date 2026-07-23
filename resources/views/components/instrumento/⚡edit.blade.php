<?php

use Livewire\Component;
use Flux\Flux;
use App\models\instrumento;
use Livewire\Attributes\On;

new class extends Component
{
    public $id;
    public $titulo;
    public $ano;

    #[On('Detail')]
    public function getIdInstrumento($id){
        $this->id = $id;

        $instrumento = instrumento::find($this->id);

        if ($instrumento){
            $this->titulo = $instrumento->titulo;
            $this->ano = $instrumento->ano;
        }
    }

    public function save(){
        $instrumento = instrumento::find($this->id);

        $instrumento->titulo = $this->titulo;
        $instrumento->ano = $this->ano;

        $validated = $this->validate([
            "titulo" => "required",
            "ano" => "required|integer",
            ]);

        if ($validated){
            try{
                if ($instrumento->save()){
                    $this->dispatch('postInsert');
                    Flux::toast(variant : "success", text: 'Registro alterar com sucesso!');
                    Flux::modal('edit')->close();
                }
            }
            catch (Throwable $e){  
                if ($e->getCode() == 23505){ 
                    Flux::toast(variant : "danger", heading: 'Falha ao alterar o registro...', text: "Já existe um instrumento com este titulo cadastrado no sistema.");
                }
                else{   
                    Flux::toast(variant : "danger", heading: 'Falha ao alterar o registro...', text : $e->getMessage());
                }
            }
        }
    }
};
?>

<flux:modal name="edit">
    <div class="flex items-center gap-4">
        <flux:icon.clipboard-document-list/>
        <flux:heading size="">Editar Instrumento de Avaliação</flux:heading>
    </div>
        <form wire:submit='save' class="flex flex-col gap-4 mt-4">
            <div class="flex gap-4">
                <div class="w-8/10">
                    <flux:input placeholder="Titulo..." wire:model='titulo'/>
                </div>
                <div class="w-2/10">
                    <flux:input placeholder="Ano..." wire:model='ano'/>
                </div>
            </div>
            <flux:button type="submit" class="mt-4" variant="primary">Salvar</flux:button>
        </form>
</flux:modal>
