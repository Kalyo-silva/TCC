<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\instrumento;
use Flux\Flux;

new class extends Component
{
    public $id;
    public $instrumento;

    #[On('Detail')]
    public function getIdInstrumento($id){
        $this->id = $id;
        $this->instrumento = instrumento::findOrFail($this->id);

    }

    public function destroy(){
        if ($this->instrumento){
            try{
                if ($this->instrumento->delete()){
                    $this->dispatch('postInsert');
                    Flux::toast(variant : "success", text: 'Registro removido com sucesso!');
                    Flux::modal('remove')->close();
                }
            }
            catch (Throwable $e){
                Flux::toast(variant : "danger", heading: 'Falha ao remover o registro...', text : "Por favor contate o administrador do sistema para mais informações. Código: ". $e->getCode());
            }
        }
    }
};
?>

<flux:modal name="remove">
    @if ($this->instrumento)
        @if ($this->instrumento->avaliacoes->count() == 0)
            <div class="flex items-center gap-2">
                <flux:heading size="lg">Remover Instrumento</flux:heading>
            </div>
            <flux:text class="mt-2">Tem certeza que deseja remover este Instrumento?</flux:text>
            <div class="flex justify-end items-center gap-2 mt-4">
                <flux:modal.close>
                    <flux:button icon='arrow-uturn-left' type="button">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button icon="trash" type="submit" variant="danger" wire:click='destroy'>Remover</flux:button>
            </div>
        @else
            <div class="flex items-center gap-2">
                <flux:icon.exclamation-triangle color="red"/>
                <flux:heading size="lg">Não é possível remover este instrumento</flux:heading>
            </div>
            <flux:text class="mt-2">Este instrumento possui avaliações vinculadas. Por este motivo, o instrumento de avaliação deverá ser mantido para preservar a integridade das avaliações realizadas.</flux:text>
            <div class="flex justify-end items-center gap-2 mt-4">
                <flux:modal.close>
                    <flux:button icon='arrow-uturn-left' type="button">Retornar</flux:button>
                </flux:modal.close>
            </div>
        @endif
    @endif
</flux:modal>