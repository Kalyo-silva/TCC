<?php

use Livewire\Component;
use Flux\Flux;
use App\models\mantenedor;

new class extends Component
{
    public $nome;
    public $cidade;
    public $uf;
    public $bairro;
    public $cep;
    public $logradouro;

    public function save(){
        $mantenedor = new mantenedor();

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
                    Flux::toast(variant : "success", text: 'Registro criado com sucesso!');
                    Flux::modal('create')->close();
                }
            }
            catch (Throwable $e){
                if ($e->getCode() == 23505){ 
                    Flux::toast(variant : "danger", heading: 'Falha ao criar o registro...', text: "Este mantenedor já está cadastrado no sistema.");
                }
                else{   
                    Flux::toast(variant : "danger", heading: 'Falha ao criar o registro...', text : $e->getMessage());
                }
            }
        }

    }
};
?>

<flux:modal name="create">
    <div class="flex items-center gap-4">
        <flux:icon.plus/>
        <flux:heading size="">Novo Mantenedor</flux:heading>
    </div>
        <form wire:submit='save' class="flex flex-col gap-4 mt-4">
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
                <flux:input placeholder="Bairro..." wire:model="bairro"/>
                <flux:input placeholder="CEP..." mask="99999-999" wire:model="cep"/>
            </div>
            <flux:input placeholder="Logradouro..." wire:model="logradouro"/>
            <flux:button type="submit" class="mt-4" variant="primary">Salvar</flux:button>
        </form>
</flux:modal>
