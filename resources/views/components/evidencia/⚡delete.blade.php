<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\evidencia;
use Flux\Flux;

new class extends Component
{
    public $evidencia;

    #[On('detailEvidenciaRemove')]
    public function getEvidencia($id){
        $this->evidencia = evidencia::find($id);

        Flux::modal('deleteEvidencia')->show();
    }

    public function destroy(){
        if ($this->evidencia){
            try{
                if ($this->evidencia->delete()){
                    $this->dispatch('load_evidencias');
                    Flux::toast(variant : "success", text: 'Registro removido com sucesso!');
                    Flux::modal('deleteEvidencia')->close();
                }
            }
            catch (Throwable $e){
                if ($e->getCode() == 23503)
                    Flux::toast(variant : "danger", heading: 'Não é possível remover este registro...', text : "Existem Avaliações que possuem esta evidência vinculada. Não é permitido a remoção de evidências que possuam vínculos a avaliações realizadas ou em andamento.");
                else
                    Flux::toast(variant : "danger", heading: 'Falha ao remover o registro...', text : $e->getMessage());
            }
        }
    }
};
?>

<flux:modal name="deleteEvidencia">
    <div class="flex items-center gap-2">
        <flux:heading size="lg">Remover Evidência</flux:heading>
    </div>
    <flux:text class="mt-2">Tem certeza que deseja remover esta evidência? </flux:text>
    <div class="flex justify-end items-center gap-2 mt-4">
        <flux:modal.close>
            <flux:button icon='arrow-uturn-left' type="button">Cancelar</flux:button>
        </flux:modal.close>
        <flux:button icon="trash" type="submit" variant="danger" wire:click='destroy'>Remover</flux:button>
    </div>
</flux:modal>