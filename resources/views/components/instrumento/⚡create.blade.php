<?php

use Livewire\Component;
use Flux\Flux;
use App\models\instrumento;

new class extends Component
{
    public $titulo;
    public $ano;

    public function save(){
        $instrumento = new instrumento();

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
                    Flux::toast(variant : "success", text: 'Registro criado com sucesso!');
                    Flux::modal('create')->close();
                }
            }
            catch (Throwable $e){  
                if ($e->getCode() == 23505){ 
                    Flux::toast(variant : "danger", heading: 'Falha ao criar o registro...', text: "Já existe um instrumento com este titulo cadastrado no sistema.");
                }
                else{   
                    Flux::toast(variant : "danger", heading: 'Falha ao criar o registro...', text : $e->getMessage());
                }
            }
        }
    }
};
?>

<flux:modal name="create">
    <div class="flex items-center gap-4">
        <flux:icon.clipboard-document-list/>
        <flux:heading size="">Novo Instrumento de Avaliação</flux:heading>
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
