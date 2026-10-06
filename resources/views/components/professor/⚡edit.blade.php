<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\professor;
use Flux\Flux;

new class extends Component
{
    public $id;
    public $nome;
    public $data_admissao;
    public $titulacao;
    public $regime;
    public $vinculo;
    public $lattes;

    #[On('Detail')]
    public function getProfId($id){
        $professor = professor::find($id);

        if ($professor){
            $this->id = $id;
            $this->nome = $professor->nome;
            $this->data_admissao = $professor->data_admissao;
            $this->titulacao = $professor->titulacao;
            $this->regime = $professor->regime;
            $this->vinculo = $professor->vinculo;
            $this->lattes = $professor->lattes;
            
        }
    }

    public function save(){
        $professor = professor::find($this->id);

        $professor->nome = $this->nome;
        $professor->data_admissao = $this->data_admissao;
        $professor->titulacao = $this->titulacao;
        $professor->regime = $this->regime;
        $professor->vinculo = $this->vinculo;
        $professor->lattes = $this->lattes;

        $validated = $this->validate(["nome" => "required"]);

        if ($validated){
            try{
                if ($professor->save()){
                    $this->dispatch('postInsert');
                    Flux::toast(variant : "success", text: 'Registro alterado com sucesso!');
                    Flux::modal('edit')->close();
                }
            }
            catch (Throwable $e){
                Flux::toast(variant : "danger", heading: 'Falha ao alterar o registro...', text : $e->getMessage());
            }
        }
    }
};
?>


<flux:modal name="edit" class="min-w-1/3">
    <div class="flex items-center gap-2">
        <flux:icon.information-circle/>
        <flux:heading size="">Editar professor</flux:heading>
    </div>
    <form wire:submit='save' class="flex flex-col gap-4 mt-4">
        <flux:input placeholder="Nome..." wire:model='nome'/>
        <div class="flex gap-4">
            <div class="w-5/10">
                <flux:input type="date" placeholder="Data de Admissao..." wire:model="data_admissao"/>
            </div>
            <div class="w-5/10">
                <flux:select wire:model="titulacao" placeholder="Titulação...">
                    <flux:select.option value="Graduado">Graduado</flux:select.option>
                    <flux:select.option value="Especialista">Especialista</flux:select.option>
                    <flux:select.option value="Mestre">Mestre</flux:select.option>
                    <flux:select.option value="Doutor">Doutor</flux:select.option>
                </flux:select>
            </div>
        </div>
            <div class="flex gap-4">
                <flux:select wire:model="regime" placeholder="Regime...">
                    <flux:select.option value="Parcial">Parcial</flux:select.option>
                    <flux:select.option value="Integral">Integral</flux:select.option>
                    <flux:select.option value="Horista">Horista</flux:select.option>
                </flux:select>

                <flux:select wire:model="vinculo" placeholder="Vinculo...">
                    <flux:select.option value="CLT">CLT</flux:select.option>
                    <flux:select.option value="Estatuário">Estatuário</flux:select.option>
                    <flux:select.option value="Outros">Outros</flux:select.option>
                </flux:select>
            </div>
        <flux:input icon="link" placeholder="Curriculum Lattes..." wire:model="lattes"/>
        <flux:button type="submit" class="mt-4" variant="primary">Salvar</flux:button>
    </form>
</flux:modal>

