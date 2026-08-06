<?php

use Livewire\Component;
use Flux\Flux;
use App\Models\instrumento;
use App\Models\curso;
use App\Models\avaliacao;

new class extends Component
{

    public $instrumento_id;
    public $curso_id;
    public $descricao;
    public $ano;
    public $data_inicio;
    public $data_fim;

    public function save(){
        $avaliacao = new avaliacao();

        $avaliacao->instrumento_id = $this->instrumento_id;
        $avaliacao->curso_id = $this->curso_id;
        $avaliacao->descricao = $this->descricao;
        $avaliacao->ano = $this->ano;
        $avaliacao->data_inicio = $this->data_inicio;
        $avaliacao->data_fim = $this->data_fim;
        $avaliacao->usuario_id = auth()->user()->id;

        $validated = $this->validate([
            "descricao" => "required",
            "instrumento_id" => "required|integer",
            "curso_id" => "required|integer",
            "ano" => "required|integer",
            "data_inicio" => "required|date",
            "data_fim" => "required|date",
            ]);

        if ($validated){
            try{
                if ($avaliacao->save()){
                    $this->dispatch('postInsert');
                    Flux::toast(variant : "success", text: 'Registro criado com sucesso!');
                    Flux::modal('create')->close();
                }
            }
            catch (Throwable $e){  
                if ($e->getCode() == 23505){ 
                    Flux::toast(variant : "danger", heading: 'Falha ao criar o registro...', text: "Já existe uma Avaliação com este titulo cadastrado no sistema.");
                }
                else{   
                    Flux::toast(variant : "danger", heading: 'Falha ao criar o registro...', text : $e->getMessage());
                }
            }
        }
    }

    public function instrumentos(){
        return instrumento::get();
    }

    public function cursos(){
        return curso::get();
    }
};
?>

<flux:modal name="create">
    <div class="flex items-center gap-4">
        <flux:icon.plus/>
        <flux:heading size="">Nova Avaliação</flux:heading>
    </div>
        <form wire:submit='save' class="flex flex-col gap-4 mt-4">
            <div class="flex gap-4">
                <div class="w-8/10">
                    <flux:input label="Descrição..." wire:model='descricao'/>
                </div>
                <div class="w-2/10">
                    <flux:input label="Ano..." wire:model='ano'/>
                </div>
            </div>
            <flux:select label="Curso..."  wire:model='curso_id'>
                    <flux:select.option value="dummy">Selecione</flux:select.option>
                @foreach ($this->cursos() as $cur)
                    <flux:select.option value="{{ $cur->id }}">{{ $cur->nome }}</flux:select.option>
                @endforeach

            </flux:select>
            <flux:select label="Instrumento de Avaliação..." wire:model='instrumento_id'>
                    <flux:select.option value="dummy">Selecione</flux:select.option>
                @foreach ($this->instrumentos() as $inst)
                    <flux:select.option value="{{ $inst->id }}">{{ $inst->titulo }}</flux:select.option>
                @endforeach

            </flux:select>
            <div class="flex gap-4">
                <div class="w-5/10">
                    <flux:input label="Data Inicial" type='date' wire:model='data_inicio'/>
                </div>
                <div class="w-5/10">
                    <flux:input label="Data Final" type='date' wire:model='data_fim'/>
                </div>
            </div>
            <flux:button type="submit" class="mt-4" variant="primary">Salvar</flux:button>
        </form>
</flux:modal>
