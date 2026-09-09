<?php

use Livewire\Component;
use Flux\Flux;
use App\Models\professor;
use App\Models\instituicao;
use App\Models\curso;

new class extends Component
{
    public $nome;
    public $instituicao_id;

    public function save(){
        $curso = new curso();

        $curso->nome = $this->nome;
        $curso->instituicao_id = $this->instituicao_id;

        $validated = $this->validate([
            "nome" => "required",
            "instituicao_id" => "required|integer"
            ]);

        if ($validated){
            try{
                if ($curso->save()){
                    $this->dispatch('postInsert');
                    $this->reset();
                    Flux::toast(variant : "success", text: 'Registro criado com sucesso!');
                    Flux::modal('create')->close();
                }
            }
            catch (Throwable $e){  
                Flux::toast(variant : "danger", heading: 'Falha ao criar o registro...', text : $e->getMessage());
            }
        }
    }

    public function instituicoes(){
        return instituicao::orderBy('nome', 'asc')->get();
    }
};
?>

<flux:modal name="create">
    <div class="flex items-center gap-4">
        <flux:icon.plus/>
        <flux:heading size="">Novo Curso</flux:heading>
    </div>
        <form wire:submit='save' class="flex flex-col gap-4 mt-4">
            <flux:input placeholder="Nome..." wire:model='nome'/>
            <div class="flex gap-4">
                <div class="w-full">
                    <flux:input.group label="Instituição">
                        <flux:button icon='academic-cap'/>
                        <flux:select wire:model='instituicao_id'>
                            <flux:select.option value="dummy">Instituição...</flux:select.option>
                            @foreach ($this->instituicoes() as $inst)
                                <flux:select.option value="{{ $inst->id }}">{{ $inst->nome }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </flux:input.group>
                </div>
            </div>
            
            <flux:button type="submit" class="mt-4" variant="primary">Salvar</flux:button>
        </form>
</flux:modal>
