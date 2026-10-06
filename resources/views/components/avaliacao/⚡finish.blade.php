<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\avaliacao;
use App\Models\avaliacao_indicador;
use Flux\Flux;

new class extends Component
{
    public $avaliacao_id;
    public $avaliacao;

    public function mount(){
        $this->avaliacao = avaliacao::find($this->avaliacao_id);
    }

    public function concluir(){
        $avaind_invalido = avaliacao_indicador::where('avaliacao_id', $this->avaliacao->id)->where('nota', 0)->first();

        if ($avaind_invalido){
            Flux::modal('finish')->close();
            Flux::toast(variant : "danger", heading: 'Não é possivel concluir a avaliação...', text : 'Existem um ou mais indicadores com notas inválidas. Por favor, revise os indicadores e tente novamente.');
        }
        else{
            if ($this->avaliacao){
                $this->avaliacao->situacao = 2;

                try{
                    if ($this->avaliacao->save()){
                        $this->dispatch('postInsert');
                        Flux::toast(variant : "success", text: 'Avaliação concluida com sucesso!');
                        Flux::modal('finish')->close();
                        Flux::modal('details')->close();
                    }
                }
                catch (Throwable $e){  
                    Flux::toast(variant : "danger", heading: 'Falha ao concluir a avaliação...', text : $e->getMessage());
                }
            }

        }
    }
};
?>

<flux:modal name="finish">
    <div class="flex items-center gap-2">
        <flux:heading size="lg">Concluir Avaliação</flux:heading>
    </div>
    <flux:text class="mt-2">Tem certeza que deseja concluir esta avaliação?</flux:text>
    <div class="flex justify-end items-center gap-2 mt-4">
        <flux:modal.close>
            <flux:button icon='arrow-uturn-left' type="button">Cancelar</flux:button>
        </flux:modal.close>
        <flux:button icon="check-circle" type="submit" variant="primary" wire:click='concluir'>Concluir</flux:button>
    </div>
</flux:modal>