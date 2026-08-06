<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\curso;
use Flux\Flux;

new class extends Component
{
    public $id;

    #[On('Detail')]
    public function getIdCurso($id){
        $this->id = $id;
    }

    public function destroy(){
        $curso = curso::findOrFail($this->id);

        if ($curso){
            try{
                if ($curso->delete()){
                    $this->dispatch('postInsert');
                    Flux::toast(variant : "success", text: 'Registro removido com sucesso!');
                    Flux::modal('remove')->close();
                }
            }
            catch (Throwable $e){
                Flux::toast(variant : "danger", heading: 'Falha ao remover o registro...', text : $e->getMessage());
            }
        }
    }
};
?>

<flux:modal name="remove">
    <div class="flex items-center gap-2">
        <flux:heading size="lg">Remover Curso</flux:heading>
    </div>
    <flux:text class="mt-2">Tem certeza que deseja remover este curso?</flux:text>
    <div class="flex justify-end items-center gap-2 mt-4">
        <flux:modal.close>
            <flux:button icon='arrow-uturn-left' type="button">Cancelar</flux:button>
        </flux:modal.close>
        <flux:button icon="trash" type="submit" variant="danger" wire:click='destroy'>Remover</flux:button>
    </div>
</flux:modal>