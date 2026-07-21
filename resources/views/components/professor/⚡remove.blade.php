<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\models\professor;
use Flux\Flux;

new class extends Component
{
    public $id;

    #[On('Detail')]
    public function getProfId($id){
        $this->id = $id;
    }

    public function destroy(){
        $professor = professor::findOrFail($this->id);

        if ($professor){
            try{
                if ($professor->delete()){
                    $this->dispatch('postInsert');
                    Flux::toast(variant : "success", text: 'Registro removido com sucesso!');
                    Flux::modal('remove')->close();
                }
            }
            catch (Throwable $e){
                if ($e->getCode() == 23503)
                    Flux::toast(variant : "danger", heading: 'Não é possível remover este registro...', text : "Existem cursos vinculados a este professor. Caso deseje remove-lo, por favor desvincule os cursos primeiro.");
                else
                    Flux::toast(variant : "danger", heading: 'Falha ao remover o registro...', text : $e->getMessage());
            }
        }
    }
};
?>

<flux:modal name="remove">
    <div class="flex items-center gap-2">
        <flux:heading size="lg">Remover Professor</flux:heading>
    </div>
    <flux:text class="mt-2">Tem certeza que deseja remover este professor?</flux:text>
    <div class="flex justify-end items-center gap-2 mt-4">
        <flux:modal.close>
            <flux:button icon='arrow-uturn-left' type="button">Cancelar</flux:button>
        </flux:modal.close>
        <flux:button icon="trash" type="submit" variant="danger" wire:click='destroy'>Remover</flux:button>
    </div>
</flux:modal>