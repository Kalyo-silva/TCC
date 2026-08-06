<?php

use Livewire\Component;
use Flux\Flux;
use App\Models\professor;

new class extends Component
{
    public $nome;
    public $data_admissao;
    public $titulacao;
    public $regime;
    public $vinculo;
    public $lattes;

    public function save(){
        $professor = new professor();

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
                    Flux::toast(variant : "success", text: 'Registro criado com sucesso!');
                    Flux::modal('create')->close();
                }
            }
            catch (Throwable $e){  
                Flux::toast(variant : "danger", heading: 'Falha ao criar o registro...', text : $e->getMessage());
            }
        }

    }
};
?>

<flux:modal name="create">
    <div class="flex items-center gap-4">
        <flux:icon.plus/>
        <flux:heading size="">Novo Professor</flux:heading>
    </div>
        <form wire:submit='save' class="flex flex-col gap-4 mt-4">
            <flux:input placeholder="Nome..." wire:model='nome'/>
            <div class="flex gap-4">
                <div class="w-5/10">
                    <flux:input type="date" placeholder="Data de Admissao..." wire:model="data_admissao"/>
                </div>
                <div class="w-5/10">
                    <flux:input placeholder="Titulação..." wire:model="titulacao"/>
                </div>
            </div>
            <div class="flex gap-4">
                <flux:input placeholder="Regime..." wire:model="regime"/>
                <flux:input placeholder="Vinculo..." wire:model="vinculo"/>
            </div>
            <flux:input icon="link" placeholder="Curriculum Lattes..." wire:model="lattes"/>
            <flux:button type="submit" class="mt-4" variant="primary">Salvar</flux:button>
        </form>
</flux:modal>
