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
    public $professor_id;

    public function save(){
        $curso = new curso();

        $curso->nome = $this->nome;
        $curso->instituicao_id = $this->instituicao_id;
        $curso->professor_id = $this->professor_id;

        $validated = $this->validate([
            "nome" => "required",
            "instituicao_id" => "required|integer",
            "professor_id" => "required|integer",
            ]);

        if ($validated){
            try{
                if ($curso->save()){
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

    public function professores(){
        return professor::orderBy('nome', 'asc')->get();
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
                <div class="w-5/10">

                <flux:input.group label="Professor">
                    <flux:button icon='user-circle'/>
                    <flux:select wire:model='professor_id'>
                        <flux:select.option value="dummy">Professor...</flux:select.option>
                        @foreach ($this->professores() as $prof)
                            <flux:select.option value="{{ $prof->id }}">{{ $prof->nome }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </flux:input.group>
                </div>
                <div class="w-5/10">
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
