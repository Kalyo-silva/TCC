<?php

use Livewire\Component;
use App\Models\evidencia;

new class extends Component
{
    public $documentos = True;
    public $imagens = True;
    public $videos = True;
    public $audios = True;
    public $textos = True;
    public $links = True;

    public $lista_images = [];
    public $recentes;

    protected $listeners = ['postInsert' => '$refresh'];

    public function mount(){
        $images = evidencia::where('tipo', 2)->get();

        $this->recentes = evidencia::orderBy('created_at', 'desc')->get();

        array_push($this->lista_images, $images);
    }
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
                <flux:menu.checkbox wire:model.live='documentos'>Documentos</flux:menu.checkbox>
                <flux:menu.checkbox wire:model.live='imagens'>Imagens</flux:menu.checkbox>
                <flux:menu.checkbox wire:model.live='videos'>Vídeos</flux:menu.checkbox>
                <flux:menu.checkbox wire:model.live='audios'>Áudios</flux:menu.checkbox>
                <flux:menu.checkbox wire:model.live='textos'>Textos</flux:menu.checkbox>
                <flux:menu.checkbox wire:model.live='links'>Links</flux:menu.checkbox>
            </flux:menu>
        
        </flux:dropdown>

        <div>
            <flux:input size="sm"  placeholder="Pesquise evidências..." onchange="Livewire.dispatch('search', {s : this.value})" icon="magnifying-glass"/>
        </div>

    </div>
    <div class="w-full h-10/12 overflow-y-scroll flex gap-4">
        <div class="w-4/6 flex flex-col gap-4">
            <div class="flex flex-col gap-4">
                <flux:badge rounded icon="clock" size="lg" class="w-fit">Adicionados recentemente</flux:badge>

                <div class="grid grid-cols-5 gap-4 w-full ">
                    @foreach ($this->recentes as $recente)
                        <flux:card class="h-10 flex items-end gap-2 px-4 py-2 cursor-pointer hover:border-2">
                            @if($recente->tipo == 1)
                                <flux:icon.document class="size-6"/>
                            @elseif($recente->tipo == 2)
                                <flux:icon.photo class="size-6"/>
                            @elseif($recente->tipo == 3)
                                <flux:icon.video-camera class="size-6"/>
                            @elseif($recente->tipo == 4)
                                <flux:icon.speaker-wave class="size-6"/>
                            @elseif($recente->tipo == 5)
                                <flux:icon.book-open class="size-6"/>
                            @elseif($recente->tipo == 6)
                                <flux:icon.paper-clip class="size-6"/>
                            @endif
                            <flux:heading class="underline truncate">{{$recente->titulo}}</flux:heading>
                        </flux:card>
                    @endforeach
                </div>
            </div>

            @if($this->documentos)
                <div>
                    <flux:badge rounded icon="document" size="lg" class="w-fit">Documentos</flux:badge>
                </div>
            @endif

            @if($this->imagens)
            <div class="flex flex-col gap-4">
                <flux:badge rounded icon="photo" size="lg" class="w-fit">Imagens</flux:badge>

                <div class="flex flex-col gap-4 w-fit ">
                    @foreach ($this->lista_images as $images)
                        <div class="flex flex-row items-center gap-4">
                            @foreach ($images as $img)
                                <img src="{{ asset('storage/evidencias/'.$img->file_path) }}" class="h-16 rounded-lg border hover:border-2 cursor-pointer">    
                            @endforeach
                        </div>
                        @endforeach
                </div>

                <div class="flex w-full justify-center">
                    <flux:button variant="subtle" icon="plus" size="xs">Mais Imagens</flux:button>
                </div>
            </div>
            @endif

            @if($this->videos)
            <div>
                <flux:badge rounded icon="video-camera" size="lg" class="w-fit">Vídeos</flux:badge>
            </div>
            @endif

            @if($this->audios)
            <div>
                <flux:badge rounded icon="speaker-wave" size="lg" class="w-fit">Áudios</flux:badge>
            </div>
            @endif

            @if($this->textos)
            <div>
                <flux:badge rounded icon="book-open" size="lg" class="w-fit">Textos</flux:badge>
            </div>
            @endif

            @if($this->links)
            <div>
                <flux:badge rounded icon="paper-clip" size="lg" class="w-fit">Links</flux:badge>
            </div>
            @endif

        </div>
        <flux:card class="w-2/6">

        </flux:card>
    </div>
    
    <div class="w-full h-1/12 flex flex-row-reverse items-center mt-2">
        <flux:button size="sm" icon:trailing="paper-airplane" disabled>Selecionar</flux:button>
    </div>
    
    <livewire:evidencia.upload/>
</flux:modal>