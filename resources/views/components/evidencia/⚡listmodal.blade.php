<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<flux:modal name='evidencia_list' class="flex flex-col justify-center gap-4 min-w-7xl max-w7xl max-h-2/3">
    <div class="flex items-center justify-between h-2/12">
        <div class="flex items-center gap-2">
            <flux:icon.paper-clip/>
            <flux:heading size="">Gerenciador de Evidências</flux:heading>
        </div>

    </div>
    <div class="flex flex-col gap-2 h-8/12 overflow-y-scroll">
        <flux:card>
            <flux:badge>Documentos</flux:badge>
            <flux:text class="w-full text-center">Não há evidências nesta seção...</flux:text>
        </flux:card>
        <flux:card>
            <flux:badge>Imagens</flux:badge>
            <flux:text class="w-full text-center">Não há evidências nesta seção...</flux:text>
        </flux:card>
        <flux:card>
            <flux:badge>Vídeos</flux:badge>
            <flux:text class="w-full text-center">Não há evidências nesta seção...</flux:text>
        </flux:card>
        <flux:card>
            <flux:badge>Áudios</flux:badge>
            <flux:text class="w-full text-center">Não há evidências nesta seção...</flux:text>
        </flux:card>
        <flux:card>
            <flux:badge>Textos</flux:badge>
            <flux:text class="w-full text-center">Não há evidências nesta seção...</flux:text>
        </flux:card>
        <flux:card>
            <flux:badge>Links</flux:badge>
            <flux:text class="w-full text-center">Não há evidências nesta seção...</flux:text>
        </flux:card>
    </div>
    <div class="flex flex-row-reverse gap-4 h-2/12">
        <flux:modal.trigger name='create'>
            <flux:button icon="plus">Novo</flux:button>
        </flux:modal.trigger>
    </div>

    <livewire:evidencia.upload/>
</flux:modal>