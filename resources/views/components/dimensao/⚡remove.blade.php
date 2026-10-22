<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\dimensao;
use Flux\Flux;

new class extends Component
{
    public $id;
    public $dimensao;

    #[On('DetailDimensao')]
    public function getIdDimensao($id){
        $this->id = $id;

        $this->dimensao = dimensao::findOrFail($this->id);
    }

    public function destroy(){
        if ($this->dimensao){
            try{
                if ($this->dimensao->delete()){
                    $this->dispatch('postInsert');
                    Flux::toast(variant : "success", text: 'Registro removido com sucesso!');
                    Flux::modal('remove_dimensao')->close();
                }
            }
            catch (Throwable $e){
                Flux::toast(variant : "danger", heading: 'Falha ao remover o registro...', text : "Por favor contate o administrador do sistema para mais informações. Código: ". $e->getCode());
            }
        }
    }
};
?>

<flux:modal name="remove_dimensao">
    @if ($this->dimensao)
        @if ($this->dimensao->indicadores->count() == 0)
            <div class="flex items-center gap-2">
                <flux:heading size="lg">Remover Dimensão</flux:heading>
            </div>
            <flux:text class="mt-2">Tem certeza que deseja remover esta dimensão?</flux:text>
            <div class="flex justify-end items-center gap-2 mt-4">
                <flux:modal.close>
                    <flux:button icon='arrow-uturn-left' type="button">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button icon="trash" type="submit" variant="danger" wire:click='destroy'>Remover</flux:button>
            </div>
        @else
            <div class="flex items-center gap-2">
                <flux:icon.exclamation-triangle color="red"/>
                <flux:heading size="lg">Não é possível remover esta dimensão</flux:heading>
            </div>
            <flux:text class="mt-2">Esta dimensão possui indicadores vinculados. para remove-la, primeiro remova os indicadores desta dimensão.</flux:text>
            <div class="flex justify-end items-center gap-2 mt-4">
                <flux:modal.close>
                    <flux:button icon='arrow-uturn-left' type="button">Retornar</flux:button>
                </flux:modal.close>
            </div>
        @endif
    @endif
</flux:modal>