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
                if ($this->evidencia->file_path){
                    Storage::disk('public')->delete('evidencias/'.$this->evidencia->file_path);
                }
                if ($this->evidencia->delete()){
                    $this->dispatch('load_evidencias');
                    Flux::toast(variant : "success", text: 'Registro removido com sucesso!');
                    Flux::modal('deleteEvidencia')->close();
                }
            }
            catch (Throwable $e){
                Flux::toast(variant : "danger", heading: 'Falha ao remover o registro...', text : "Por favor contate o administrador do sistema para mais informações. Código: ". $e->getCode());
            }
        }
    }
};
?>

<flux:modal name="deleteEvidencia">
    @if ($this->evidencia)
        @if ($this->evidencia->avaliacoes->count() == 0)
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
        @else
            <div class="flex items-center gap-2">
                <flux:icon.exclamation-triangle color="red"/>
                <flux:heading size="lg">Não é possível remover esta evidência</flux:heading>
            </div>
            <flux:text class="mt-2">Esta evidência está vinculada a uma avaliação realizada. Devido a isso, não é possivel removê-la para não comprometer com a integridade da avaliação.</flux:text>
            <div class="flex justify-end items-center gap-2 mt-4">
                <flux:modal.close>
                    <flux:button icon='arrow-uturn-left' type="button">Retornar</flux:button>
                </flux:modal.close>
            </div>
        @endif
    @endif
</flux:modal>