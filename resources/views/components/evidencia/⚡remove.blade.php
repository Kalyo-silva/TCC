<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\avaliacao_evidencia;
use Flux\Flux;

new class extends Component
{
    public $evidencia;

    #[On('detailEvidenciaRemove')]
    public function getEvidencia($id, $avaliacao_indicador_id){
        $this->evidencia = avaliacao_evidencia::where('avaliacao_indicador_id', $avaliacao_indicador_id)->where('evidencia_id', $id)->first();

        Flux::modal('removeEvidencia')->show();
    }

    public function destroy(){
        if ($this->evidencia){
            try{
                if ($this->evidencia->delete()){
                    $this->dispatch('update_avaliacao_evidencia');
                    Flux::toast(variant : "success", text: 'Registro removido com sucesso!');
                    Flux::modal('removeEvidencia')->close();
                }
            }
            catch (Throwable $e){
                Flux::toast(variant : "danger", heading: 'Falha ao remover o registro...', text : $e->getMessage());
            }
        }
    }
};
?>

<flux:modal name="removeEvidencia">
    <div class="flex items-center gap-2">
        <flux:heading size="lg">Desvincular Evidência</flux:heading>
    </div>
    <flux:text class="mt-2">Tem certeza que deseja remover esta evidência do indicador? </flux:text>
    <div class="flex justify-end items-center gap-2 mt-4">
        <flux:modal.close>
            <flux:button icon='arrow-uturn-left' type="button">Cancelar</flux:button>
        </flux:modal.close>
        <flux:button icon="trash" type="submit" variant="danger" wire:click='destroy'>Remover</flux:button>
    </div>
</flux:modal>