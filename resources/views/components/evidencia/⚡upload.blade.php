<?php

use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public $file;
    public $titulo;
    public $ano;
};
?>

<flux:modal name='create' class="min-w-2xl flex flex-col gap-4">
    <div class="flex items-center gap-2">
        <flux:icon.paper-clip/>
        <flux:heading size="">Nova Evidência</flux:heading>
    </div>
    <form class="flex flex-col gap-4">
        <div class="flex items-center gap-4">
            <div class="w-3/4">
                <flux:input placeholder="Titulo..." wire:model='titulo'/>
            </div>
            <div class="w-1/4">
                <flux:input placeholder="Ano..." wire:model='ano' type="number"/>
            </div>
        </div>
        <flux:select wire:model="tipo" placeholder='Tipo...'> 
            <flux:select.option value='1'>Documento</flux:select.option>
            <flux:select.option value='2'>Imagem</flux:select.option>
            <flux:select.option value='3'>Vídeo</flux:select.option>
            <flux:select.option value='4'>Áudio</flux:select.option>
            <flux:select.option value='5'>Texto</flux:select.option>
            <flux:select.option value='6'>Link</flux:select.option>
        </flux:select>
    </form>
</flux:modal>