<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<flux:modal name='evidencia_list' class="flex flex-col p-1 min-w-7xl max-w7xl h-4/5 overflow-hidden">
    <div class="w-full h-1/12 flex items-center gap-2 mb-2 mt-2">    
        <flux:modal.trigger name='upload'>
            <flux:button size="sm" icon="document-plus">Novo</flux:button>
        </flux:modal.trigger>

        <flux:dropdown>
            <flux:button size="sm" icon="funnel">Arquivos</flux:button>
            
            <flux:menu>
                <flux:menu.checkbox >Documentos</flux:menu.checkbox>
                <flux:menu.checkbox >Imagens</flux:menu.checkbox>
                <flux:menu.checkbox >Vídeos</flux:menu.checkbox>
                <flux:menu.checkbox >Áudios</flux:menu.checkbox>
                <flux:menu.checkbox >Textos</flux:menu.checkbox>
                <flux:menu.checkbox >Links</flux:menu.checkbox>
            </flux:menu>
        
        </flux:dropdown>
        
        <div>
            <flux:input size="sm"  placeholder="Pesquise evidências..." onchange="Livewire.dispatch('search', {s : this.value})" icon="magnifying-glass"/>
        </div>

    </div>
    <div class="w-full h-10/12 overflow-y-scroll flex gap-2">
        <div class="w-4/6 flex flex-col gap-2">

            <div>
                <flux:badge rounded icon="clock" size="lg" class="w-fit">Adicionados recentemente</flux:badge>
            </div>

            <div>
                <flux:badge rounded icon="document" size="lg" class="w-fit">Documentos</flux:badge>
            </div>

            <div>
                <flux:badge rounded icon="photo" size="lg" class="w-fit">Imagens</flux:badge>
            </div>

            <div>
                <flux:badge rounded icon="video-camera" size="lg" class="w-fit">Vídeos</flux:badge>
            </div>

            <div>
                <flux:badge rounded icon="speaker-wave" size="lg" class="w-fit">Áudios</flux:badge>
            </div>

            <div>
                <flux:badge rounded icon="book-open" size="lg" class="w-fit">Textos</flux:badge>
            </div>

            <div>
                <flux:badge rounded icon="paper-clip" size="lg" class="w-fit">Links</flux:badge>
            </div>

        </div>
        <flux:card class="w-2/6">

        </flux:card>
    </div>
    
    <div class="w-full h-1/12 flex flex-row-reverse items-center mt-2">
        <flux:button size="sm" icon:trailing="paper-airplane" disabled>Selecionar</flux:button>
    </div>
    
    <livewire:evidencia.upload/>
</flux:modal>