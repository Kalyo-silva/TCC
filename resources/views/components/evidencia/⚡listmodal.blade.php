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
    public $lista_documentos = [];
    public $lista_videos = [];
    public $lista_audios = [];
    public $lista_textos = [];
    public $lista_links = [];
    public $recentes;

    protected $listeners = ['postInsert' => '$refresh'];

    public function mount(){
        $documentos = evidencia::where('tipo', 1)->get();
        $images = evidencia::where('tipo', 2)->get();
        $videos = evidencia::where('tipo', 3)->get();
        $audios = evidencia::where('tipo', 4)->get();
        $textos = evidencia::where('tipo', 5)->get();
        $links = evidencia::where('tipo', 6)->get();

        $this->recentes = evidencia::orderBy('created_at', 'desc')->take(5)->get();

        array_push($this->lista_documentos, $documentos);
        array_push($this->lista_images, $images);
        array_push($this->lista_videos, $videos);
        array_push($this->lista_audios, $audios);
        array_push($this->lista_textos, $textos);
        array_push($this->lista_links, $links);
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
    <div class="w-full h-10/12 overflow-y-scroll items-stretch flex gap-4">
        <div class="w-4/6 flex flex-col gap-4">
            <div class="flex flex-col gap-4">
                <flux:badge rounded icon="clock" size="lg" class="w-fit">Adicionados recentemente</flux:badge>

                <div class="grid grid-cols-5 gap-4 w-full ">
                    @foreach ($this->recentes as $recente)
                        <flux:tooltip content="{{ $recente->file_name ? $recente->file_name : ($recente->link ? $recente->link : 'Visualizar') }}">
                            <flux:card class="h-12 w-full flex justify-start items-center gap-2 px-4 py-2 cursor-pointer hover:border-2 overflow-hidden">
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
                        </flux:tooltip>
                    @endforeach
                </div>
            </div>

            @if($this->documentos)
                <div class="flex flex-col gap-4">
                    <flux:badge rounded icon="document" size="lg" class="w-fit">Documentos</flux:badge>
                        @foreach ($this->lista_documentos as $documentos)
                            <div class="grid grid-cols-4 gap-4 w-full ">
                                @foreach ($documentos as $docs)
                                <flux:tooltip content="{{ $docs->file_name }}">
                                    <flux:card class="h-12 w-full hover:border-2 border rounded-lg cursor-pointer flex justify-start gap-2 items-center overflow-hidden px-4 py-2"> 
                                        <flux:icon.document class="size-6"/>
                                        <flux:heading class="underline truncate">{{$docs->titulo}}</flux:heading>
                                    </flux:card>
                                </flux:tooltip>
                                @endforeach
                            </div>
                        @endforeach

                    @if (count($this->lista_documentos) != 1)
                        <div class="flex w-full justify-center">
                            <flux:button variant="subtle" icon="plus" size="xs">Mais Documentos</flux:button>
                        </div>
                    @endif
                </div>
            @endif

            @if($this->imagens)
            <div class="flex flex-col gap-4">
                <flux:badge rounded icon="photo" size="lg" class="w-fit">Imagens</flux:badge>

                <div class="flex flex-col gap-4 w-fit ">
                    @foreach ($this->lista_images as $images)
                        <div class="flex flex-row items-center gap-4">
                            @foreach ($images as $img)
                                <flux:tooltip content="{{ $img->file_name }}">
                                    <flux:card class="p-0 overflow-hidden">   
                                        <img src="{{ asset('storage/evidencias/'.$img->file_path) }}" class="max-h-32 border hover:border-2 cursor-pointe">    
                                        <flux:text class="px-2 py-1 text-center">{{$img->titulo}}</flux:text>
                                    </flux:card>
                                </flux:tooltip>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                @if (count($this->lista_images) != 1)
                    <div class="flex w-full justify-center">
                        <flux:button variant="subtle" icon="plus" size="xs">Mais Imagens</flux:button>
                    </div>
                @endif
            </div>
            @endif

            @if($this->videos)
            <div class="flex flex-col gap-4">
                <flux:badge rounded icon="video-camera" size="lg" class="w-fit">Vídeos</flux:badge>
        
                <div class="flex flex-col gap-4 w-fit ">
                    @foreach ($this->lista_videos as $videos)
                        <div class="flex flex-row items-center gap-4">
                            @foreach ($videos as $vids)
                                <flux:tooltip content="{{ $vids->file_name }}">
                                    <flux:card class="p-0 overflow-hidden border hover:border-2 cursor-pointer">   
                                        <video src="{{ asset('storage/evidencias/'.$vids->file_path) }}" controls class="max-h-32"> </video>  
                                        <flux:text class="px-2 py-1 text-center">{{$vids->titulo}}</flux:text>
                                    </flux:card> 
                                </flux:tooltip>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                @if (count($this->lista_videos) != 1)
                    <div class="flex w-full justify-center">
                        <flux:button variant="subtle" icon="plus" size="xs">Mais Vídeos</flux:button>
                    </div>
                @endif
            </div>
            @endif

            @if($this->audios)
            <div class="flex flex-col gap-4">
                <flux:badge rounded icon="speaker-wave" size="lg" class="w-fit">Áudios</flux:badge>

               
                <div class="flex flex-col gap-4 w-fit ">
                    @foreach ($this->lista_audios as $audios)
                        <div class="flex flex-row items-center gap-4">
                            @foreach ($audios as $auds)
                                <flux:tooltip content="{{ $auds->file_name }}">
                                    <audio src="{{ asset('storage/evidencias/'.$auds->file_path) }}" controls class="max-w-64 rounded-lg border hover:border-2 cursor-pointer overflow-hidden"> </audio>   
                                </flux:tooltip>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                @if (count($this->lista_audios) != 1)
                    <div class="flex w-full justify-center">
                        <flux:button variant="subtle" icon="plus" size="xs">Mais Áudios</flux:button>
                    </div>
                @endif
            </div>
            @endif

            @if($this->textos)
            <div class="flex flex-col gap-4">
                <flux:badge rounded icon="book-open" size="lg" class="w-fit">Textos</flux:badge>

                <div class="flex flex-col gap-4 ">
                    @foreach ($this->lista_textos as $textos)
                        <div class="flex flex-row items-center gap-4 w-full">
                            @foreach ($textos as $txt)
                                <flux:tooltip content="visualizar" class="max-w-1/2">
                                    <flux:card class="h-12 w-full hover:border-2 border rounded-lg cursor-pointer flex justify-start gap-2 items-center overflow-hidden px-4 py-2"> 
                                        <flux:icon.book-open class="size-6"/>
                                        <flux:heading class="underline truncate">{{$txt->titulo}}</flux:heading>
                                    </flux:card>
                                </flux:tooltip>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                @if (count($this->lista_textos) != 1)
                    <div class="flex w-full justify-center">
                        <flux:button variant="subtle" icon="plus" size="xs">Mais Textos</flux:button>
                    </div>
                @endif
            </div>
            @endif

            @if($this->links)
            <div class="flex flex-col gap-4">
                <flux:badge rounded icon="paper-clip" size="lg" class="w-fit">Links</flux:badge>

                <div class="flex flex-col gap-4 ">
                    @foreach ($this->lista_links as $links)
                        <div class="flex flex-row items-center gap-4 w-full">
                            @foreach ($links as $lnk)
                                <flux:tooltip content="{{$lnk->link}}" class="max-w-1/2">
                                    <flux:card class="h-12 w-full hover:border-2 border rounded-lg cursor-pointer flex justify-start gap-2 items-center overflow-hidden px-4 py-2"> 
                                        <flux:icon.paper-clip class="size-6"/>
                                        <flux:heading class="underline truncate">{{$lnk->titulo}}</flux:heading>
                                    </flux:card>
                                </flux:tooltip>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                @if (count($this->lista_links) != 1)
                    <div class="flex w-full justify-center">
                        <flux:button variant="subtle" icon="plus" size="xs">Mais Links</flux:button>
                    </div>
                @endif
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