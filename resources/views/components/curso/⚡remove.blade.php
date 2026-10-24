<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\curso;
use Flux\Flux;

new class extends Component
{
    public $id;
    public $curso;

    #[On('Detail')]
    public function getIdCurso($id){
        $this->id = $id;
        $this->curso = curso::findOrFail($this->id);
    }

    public function destroy(){
        if ($this->curso){
            try{
                if ($this->curso->delete()){
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
    @if ($this->curso)
        @if ($this->curso->professores->count() == 0 && $this->curso->avaliacoes->count() == 0)
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
        @else
            <div class="flex items-center gap-2">
                <flux:icon.exclamation-triangle color="red"/>
                <flux:heading size="lg">Não é possível remover este curso</flux:heading>
            </div>
            @if ($this->curso->avaliacoes->count() != 0)
                <flux:text class="mt-2">Este curso está vinculado a uma avaliação realizada e deve ser mantido por motivo de integridade da avaliação.</flux:text>
            @elseif ($this->curso->professores->count() != 0)
                <flux:text class="mt-2">Este curso possui professores vinculados. para removê-lo, primeiro remova os professores do corpo docente deste curso.</flux:text>
            @endif
            <div class="flex justify-end items-center gap-2 mt-4">
                <flux:modal.close>
                    <flux:button icon='arrow-uturn-left' type="button">Retornar</flux:button>
                </flux:modal.close>
            </div>
        @endif
    @endif
</flux:modal>