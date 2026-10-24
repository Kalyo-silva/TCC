<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\indicador;
use Flux\Flux;

new class extends Component
{
    public $id;
    public $indicador;

    #[On('DetailIndicador')]
    public function getIdIndicador($id){
        $this->id = $id;
        $this->indicador = indicador::findOrFail($this->id);
    }

    public function destroy(){
        if ($this->indicador){
            try{
                if ($this->indicador->delete()){
                    $this->dispatch('postInsert');
                    Flux::toast(variant : "success", text: 'Registro removido com sucesso!');
                    Flux::modal('remove_indicador')->close();
                }
            }
            catch (Throwable $e){
                Flux::toast(variant : "danger", heading: 'Falha ao remover o registro...', text : "Por favor contate o administrador do sistema para mais informações. Código: ". $e->getCode());
            }
        }
    }
};
?>

<flux:modal name="remove_indicador">
    @if ($this->indicador)
        @if ($this->indicador->criterios->count() == 0)
            <div class="flex items-center gap-2">
                <flux:heading size="lg">Remover Indicador</flux:heading>
            </div>
            <flux:text class="mt-2">Tem certeza que deseja remover este Indicador?</flux:text>
            <div class="flex justify-end items-center gap-2 mt-4">
                <flux:modal.close>
                    <flux:button icon='arrow-uturn-left' type="button">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button icon="trash" type="submit" variant="danger" wire:click='destroy'>Remover</flux:button>
            </div>
        @else
            <div class="flex items-center gap-2">
                <flux:icon.exclamation-triangle color="red"/>
                <flux:heading size="lg">Não é possível remover este indicador</flux:heading>
            </div>
            <flux:text class="mt-2">Este indicador possui critérios vinculados. para removê-lo, primeiro remova os critérios deste indicador.</flux:text>
            <div class="flex justify-end items-center gap-2 mt-4">
                <flux:modal.close>
                    <flux:button icon='arrow-uturn-left' type="button">Retornar</flux:button>
                </flux:modal.close>
            </div>
        @endif
    @endif
</flux:modal>