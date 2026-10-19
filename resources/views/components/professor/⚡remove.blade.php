<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\professor;
use Flux\Flux;

new class extends Component
{
    public $id;
    public $professor;

    #[On('Detail')]
    public function getProfId($id){
        $this->id = $id;
        $this->professor = professor::findOrFail($this->id);
    }

    public function destroy(){
        if ($this->professor){
            try{
                if ($this->professor->delete()){
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
    @if ($this->professor)
        @if ($this->professor->cursos->count() == 0 && $this->professor->avaliacoes->count() == 0)
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
        @else
            <div class="flex items-center gap-2">
                <flux:icon.exclamation-triangle color="red"/>
                <flux:heading size="lg">Não é possível remover este professor</flux:heading>
            </div>
            @if ($this->professor->avaliacoes->count() != 0)
                <flux:text class="mt-2">Este professor está vinculado ao histórico de corpo docente de uma avaliação realizada e deve ser mantido por motivo de integridade da avaliação.</flux:text>
            @elseif ($this->professor->cursos->count() != 0)
                <flux:text class="mt-2">Este professor está vinculado em um ou mais cursos. para remove-lo, primeiro remova o professor do corpo docente do curso que esta relacionado a este registro.</flux:text>
            @endif
            <div class="flex justify-end items-center gap-2 mt-4">
                <flux:modal.close>
                    <flux:button icon='arrow-uturn-left' type="button">Retornar</flux:button>
                </flux:modal.close>
            </div>
        @endif
    @endif
</flux:modal>