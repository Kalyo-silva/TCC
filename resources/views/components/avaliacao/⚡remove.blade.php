<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\avaliacao;
use App\Models\avaliacao_evidencia;
use App\Models\avaliacao_indicador;
use App\Models\avaliacao_corpodocente;
use Flux\Flux;

new class extends Component
{
    public $id;

    #[On('AvaliacaoDetail')]
    public function getIdAvaliacao($id){
        $this->id = $id;
    }

    public function destroy(){
        $avaliacao = avaliacao::findOrFail($this->id);

        if ($avaliacao){
            // remove indicador-evidencia
            try{
                $indicador_evidencia = avaliacao_evidencia::where('avaliacao_id', $this->id);

                $indicador_evidencia->delete();
            }
            catch (Throwable $e){
                Flux::toast(variant : "danger", heading: 'Falha ao remover o registro...', text : $e->getMessage());
            }
            // remove avaliacao-indicador
            try{
                $indicador_indicador = avaliacao_indicador::where('avaliacao_id', $this->id);

                $indicador_indicador->delete();
            }
            catch (Throwable $e){
                Flux::toast(variant : "danger", heading: 'Falha ao remover o registro...', text : $e->getMessage());
            }
            // remove avaliacao_corpodocente
            try{
                $avaliacao_corpodocente = avaliacao_corpodocente::where('avaliacao_id', $this->id);

                $avaliacao_corpodocente->delete();
            }
            catch (Throwable $e){
                Flux::toast(variant : "danger", heading: 'Falha ao remover o registro...', text : $e->getMessage());
            }

            // remove avaliacao
            try{
                if ($avaliacao->delete()){
                    $this->dispatch('postInsert');
                    Flux::toast(variant : "success", text: 'Registro removido com sucesso!');
                    Flux::modal('remove')->close();
                    Flux::modal('details')->close();
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
        <flux:heading size="lg">Remover Avaliação</flux:heading>
    </div>
    <flux:text class="mt-2">Tem certeza que deseja remover esta avaliação?</flux:text>
    <div class="flex justify-end items-center gap-2 mt-4">
        <flux:modal.close>
            <flux:button icon='arrow-uturn-left' type="button">Cancelar</flux:button>
        </flux:modal.close>
        <flux:button icon="trash" type="submit" variant="danger" wire:click='destroy'>Remover</flux:button>
    </div>
</flux:modal>