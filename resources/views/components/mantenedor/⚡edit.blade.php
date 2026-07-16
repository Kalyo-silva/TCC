<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\mantenedor;
use Flux\Flux;

new class extends Component
{
    public $id;
    public $nome;
    public $cidade;
    public $uf;
    public $bairro;
    public $cep;
    public $logradouro;

    #[On('MantenedorDetail')]
    public function getIdMantenedor($id){
        $mantenedor = mantenedor::find($id);

        if ($mantenedor){
            $this->id = $id;
            $this->nome = $mantenedor->nome;
            $this->cidade = $mantenedor->cidade;
            $this->uf = $mantenedor->uf;
            $this->bairro = $mantenedor->bairro;
            $this->cep = $mantenedor->cep;
            $this->logradouro = $mantenedor->logradouro;
            
        }
    }

    public function save(){
        $mantenedor = mantenedor::find($this->id);

        $mantenedor->nome = $this->nome;
        $mantenedor->cidade = $this->cidade;
        $mantenedor->uf = $this->uf;
        $mantenedor->bairro = $this->bairro;
        $mantenedor->cep = $this->cep;
        $mantenedor->logradouro = $this->logradouro;

        $validated = $this->validate(["nome" => "required"]);

        if ($validated){
            try{
                if ($mantenedor->save()){
                    $this->dispatch('postInsert');
                    Flux::toast(variant : "success", text: 'Registro alterado com sucesso!');
                    Flux::modal('edit')->close();
                }
            }
            catch (Throwable $e){
                if ($e->getCode() == 23505){ 
                    Flux::toast(variant : "danger", heading: 'Falha ao alterar o registro...', text: "Este nome já está cadastrado no sistema.");
                }
                else{   
                    Flux::toast(variant : "danger", heading: 'Falha ao alterar o registro...', text : $e->getMessage());
                }
            }
        }
    }
};
?>


<flux:modal name="edit">
    <div class="flex items-center gap-2">
        <flux:icon.information-circle/>
        <flux:heading size="">Editar Mantenedor</flux:heading>
    </div>
    <form class="flex flex-col gap-4 mt-8" wire:submit='save'>
        <flux:input placeholder="Nome..." wire:model='nome'/>
        <div class="flex gap-4">
            <div class="w-8/10">
                <flux:input placeholder="Cidade..." wire:model="cidade"/>
            </div>
            <div class="w-2/10">
                <flux:input placeholder="UF..." wire:model="uf" maxlength="2"/>
            </div>
        </div>
        <div class="flex gap-4">
            <flux:input placeholder="Bairro..." wire:model="bairro" />
            <flux:input placeholder="CEP..." wire:model="cep"/>
        </div>
        <flux:input placeholder="Logradouro..." wire:model="logradouro"/>
        
        <div class="flex items-center gap-2 justify-end mt-4">
            <flux:modal.close>
                <flux:button icon='arrow-uturn-left' type="button">Cancelar</flux:button>
            </flux:modal.close>
            <flux:button icon="check-circle" type="submit" variant="primary">Salvar</flux:button>
        </div>
        
    </form>
</flux:modal>

