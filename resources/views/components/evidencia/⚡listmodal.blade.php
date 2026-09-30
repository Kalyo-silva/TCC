<?php

use Livewire\Component;
use Livewire\Attributes\Reactive;
use App\Models\evidencia;
use App\Models\avaliacao_indicador;
use App\Models\avaliacao_evidencia;
use Livewire\Attributes\On;

new class extends Component
{
    public $documentos = True;
    public $page_documentos = 1;
    public $count_documentos = 0;
    public $lista_documentos = [];

    public $imagens = True;
    public $page_imagens = 1;
    public $count_images = 0;
    public $lista_images = [];

    public $videos = True;
    public $page_videos = 1;
    public $count_videos = 0;
    public $lista_videos = [];

    public $audios = True;
    public $page_audios = 1;
    public $count_audios = 0;
    public $lista_audios = [];

    public $textos = True;
    public $page_textos = 1;
    public $count_textos = 0;
    public $lista_textos = [];

    public $links = True;
    public $page_links = 1;
    public $count_links = 0;
    public $lista_links = [];

    public $recentes;

    public $evidencia_id;

    #[Reactive]
    public $indicador_id;
    #[Reactive]
    public $avaliacao_id;

    protected $listeners = ['postInsert' => '$refresh'];

    #[On('load_evidencias')]
    public function loadEvidencias(){
        $this->lista_documentos = evidencia::where('tipo', 1)->take(4 * $this->page_documentos)->get();
        $this->count_documentos = evidencia::where('tipo', 1)->count();

        $this->lista_images = evidencia::where('tipo', 2)->take(4 * $this->page_imagens)->get();
        $this->count_images = evidencia::where('tipo', 2)->count();

        $this->lista_videos = evidencia::where('tipo', 3)->take(4 * $this->page_videos)->get();
        $this->count_videos = evidencia::where('tipo', 3)->count();

        $this->lista_audios = evidencia::where('tipo', 4)->take(3 * $this->page_audios)->get();
        $this->count_audios = evidencia::where('tipo', 4)->count();

        $this->lista_textos = evidencia::where('tipo', 5)->take(4 * $this->page_textos)->get();
        $this->count_textos = evidencia::where('tipo', 5)->count();

        $this->lista_links = evidencia::where('tipo', 6)->take(4 * $this->page_links)->get();
        $this->count_links = evidencia::where('tipo', 6)->count();

        $this->recentes = evidencia::orderBy('created_at', 'desc')->take(4)->get();
    }

    public function mount(){
        $this->loadEvidencias();
    }

    public function nextPageDcs(){
        $this->page_documentos += 1;
        $this->lista_documentos = evidencia::where('tipo', 1)->take(4 * $this->page_documentos)->get();

        $this->dispatch('postInsert');
    }

    public function nextPageImg(){
        $this->page_imagens += 1;
        $this->lista_images = evidencia::where('tipo', 2)->take(4 * $this->page_imagens)->get();

        $this->dispatch('postInsert');
    }

    public function nextPageVids(){
        $this->page_videos += 1;
        $this->lista_videos = evidencia::where('tipo', 3)->take(4 * $this->page_videos)->get();

        $this->dispatch('postInsert');
    }

    public function nextPageAuds(){
        $this->page_audios += 1;
        $this->lista_audios = evidencia::where('tipo', 4)->take(3 * $this->page_audios)->get();

        $this->dispatch('postInsert');
    }

    public function nextPageTxts(){
        $this->page_textos += 1;
        $this->lista_textos = evidencia::where('tipo', 5)->take(4 * $this->page_textos)->get();

        $this->dispatch('postInsert');
    }

    public function nextPageLinks(){
        $this->page_links += 1;
        $this->lista_links = evidencia::where('tipo', 6)->take(4 * $this->page_links)->get();

        $this->dispatch('postInsert');
    }

    public function setEvidenciaDetails($id){
        $this->evidencia_id = $id;
    }

    public function selectEvidencia(){
        $avaind = avaliacao_indicador::where('avaliacao_id', $this->avaliacao_id)->where('indicador_id', $this->indicador_id)->first();

        if ($avaind->id){
            $avaevi = new avaliacao_evidencia();

            $avaevi->avaliacao_indicador_id = $avaind->id;
            $avaevi->evidencia_id = $this->evidencia_id;

            try{
                if ($avaevi->save()){
                    Flux::toast(variant : "success", text: 'Evidência anexada com sucesso!');
                    $this->dispatch('update_avaliacao_evidencia');
                    $this->evidencia_id = null;
                    Flux::modal('evidencia_list')->close();
                }
            }
            catch (Throwable $e){  
                Flux::toast(variant : "danger", heading: 'Falha ao anexar o registro...', text : $e->getMessage());
            }
        }
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
    <div class="w-full h-10/12 overflow-hidden items-stretch flex gap-4">
        <div class="w-4/6 flex flex-col gap-4 overflow-y-scroll pr-4">
            <div class="flex flex-col gap-4">
                <flux:badge rounded icon="clock" size="lg" class="w-fit">Adicionados recentemente</flux:badge>

                <div class="grid grid-cols-4 gap-4 w-full ">
                    @foreach ($this->recentes as $recente)
                        <flux:tooltip content="{{ $recente->file_name ? $recente->file_name : ($recente->link ? $recente->link : 'Visualizar') }}">
                            <flux:card class="h-12 w-full flex justify-start items-center gap-2 px-4 py-2 cursor-pointer hover:border-2 overflow-hidden" wire:click="setEvidenciaDetails({{$recente->id}})">
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

                    <div class="grid grid-cols-4 gap-4 w-full ">
                        @foreach ($this->lista_documentos as $docs)
                            <flux:tooltip content="{{ $docs->file_name }}">
                                <flux:card class="h-12 w-full hover:border-2 border rounded-lg cursor-pointer flex justify-start gap-2 items-center overflow-hidden px-4 py-2" wire:click="setEvidenciaDetails({{$docs->id}})"> 
                                    <flux:icon.document class="size-6"/>
                                    <flux:heading class="underline truncate">{{$docs->titulo}}</flux:heading>
                                </flux:card>
                            </flux:tooltip>
                        @endforeach
                    </div>

                    @if (count($this->lista_documentos) < $this->count_documentos)
                        <div class="flex w-full justify-center">
                            <flux:button variant="subtle" icon="plus" size="xs" wire:click="nextPageDcs()">Mais Documentos</flux:button>
                        </div>
                    @endif
                </div>
            @endif

            @if($this->imagens)
            <div class="flex flex-col gap-4">
                <flux:badge rounded icon="photo" size="lg" class="w-fit">Imagens</flux:badge>

                <div class="grid grid-cols-4 gap-4">
                    @foreach ($this->lista_images as $img)
                        <flux:tooltip content="{{ $img->file_name }}" class="w-full">
                            <flux:card class="p-0 overflow-hidden border hover:border-2 cursor-pointer w-full flex flex-col items-center" wire:click="setEvidenciaDetails({{$img->id}})">   
                                <img src="{{ asset('storage/evidencias/'.$img->file_path) }}" class="h-32 w-full object-contain bg-black">    
                                <flux:text class="px-2 py-1 text-center w-full">{{$img->titulo}}</flux:text>
                            </flux:card>
                        </flux:tooltip>
                    @endforeach
                </div>
                @if (count($this->lista_images) < $this->count_images)
                    <div class="flex w-full justify-center">
                        <flux:button variant="subtle" icon="plus" size="xs" wire:click="nextPageImg()">Mais Imagens</flux:button>
                    </div>
                @endif
            </div>
            @endif

            @if($this->videos)
            <div class="flex flex-col gap-4">
                <flux:badge rounded icon="video-camera" size="lg" class="w-fit">Vídeos</flux:badge>
        
                <div class="grid grid-cols-4 gap-4">
                    @foreach ($this->lista_videos as $vids)
                        <flux:tooltip content="{{ $vids->file_name }}" class="w-full">
                            <flux:card class="p-0 overflow-hidden border hover:border-2 cursor-pointer w-full flex flex-col items-center" wire:click="setEvidenciaDetails({{$vids->id}})">    
                                <video src="{{ asset('storage/evidencias/'.$vids->file_path) }}" controls class="h-32 w-full object-contain bg-black"> </video>  
                                <flux:text class="px-2 py-1 text-center">{{$vids->titulo}}</flux:text>
                            </flux:card> 
                        </flux:tooltip>
                    @endforeach
                </div>
                @if (count($this->lista_videos) < $this->count_videos)
                    <div class="flex w-full justify-center">
                        <flux:button variant="subtle" icon="plus" size="xs" wire:click="nextPageVids()">Mais Vídeos</flux:button>
                    </div>
                @endif
            </div>
            @endif

            @if($this->audios)
            <div class="flex flex-col gap-4">
                <flux:badge rounded icon="speaker-wave" size="lg" class="w-fit">Áudios</flux:badge>

               
                <div class="grid grid-cols-3 gap-4">
                    @foreach ($this->lista_audios as $auds)
                        <flux:tooltip content="{{ $auds->file_name }}" class="w-full">
                            <flux:card class="p-0 overflow-hidden border hover:border-2 cursor-pointer w-full flex flex-col items-center" wire:click="setEvidenciaDetails({{$auds->id}})">  
                                <audio src="{{ asset('storage/evidencias/'.$auds->file_path) }}" controls class="w-64"> </audio>   
                                <flux:text class="px-2 py-1 text-center">{{$auds->titulo}}</flux:text>
                            </flux:card>
                        </flux:tooltip>
                    @endforeach
                </div>

                @if (count($this->lista_audios) < $this->count_audios)
                    <div class="flex w-full justify-center">
                        <flux:button variant="subtle" icon="plus" size="xs" wire:click="nextPageAuds()">Mais Áudios</flux:button>
                    </div>
                @endif
            </div>
            @endif

            @if($this->textos)
            <div class="flex flex-col gap-4">
                <flux:badge rounded icon="book-open" size="lg" class="w-fit">Textos</flux:badge>

                <div class="grid grid-cols-4 gap-4 w-full ">
                    @foreach ($this->lista_textos as $txts)
                        <flux:tooltip content="Visualizar">
                            <flux:card class="h-12 w-full hover:border-2 border rounded-lg cursor-pointer flex justify-start gap-2 items-center overflow-hidden px-4 py-2" wire:click="setEvidenciaDetails({{$txts->id}})"> 
                                <flux:icon.book-open class="size-6"/>
                                <flux:heading class="underline truncate">{{$txts->titulo}}</flux:heading>
                            </flux:card>
                        </flux:tooltip>
                    @endforeach
                </div>

                @if (count($this->lista_textos) < $this->count_textos)
                    <div class="flex w-full justify-center">
                        <flux:button variant="subtle" icon="plus" size="xs" wire:click="nextPageTxts()">Mais Textos</flux:button>
                    </div>
                @endif
            </div>
            @endif

            @if($this->links)
            <div class="flex flex-col gap-4">
                <flux:badge rounded icon="paper-clip" size="lg" class="w-fit">Links</flux:badge>

                <div class="grid grid-cols-4 gap-4 w-full ">
                    @foreach ($this->lista_links as $link)
                        <flux:tooltip content="{{ $link->link }}">
                            <flux:card class="h-12 w-full hover:border-2 border rounded-lg cursor-pointer flex justify-start gap-2 items-center overflow-hidden px-4 py-2" wire:click="setEvidenciaDetails({{$link->id}})"> 
                                <flux:icon.paper-clip class="size-6"/>
                                <flux:heading class="underline truncate">{{$link->titulo}}</flux:heading>
                            </flux:card>
                        </flux:tooltip>
                    @endforeach
                </div>

                @if (count($this->lista_links) < $this->count_links)
                    <div class="flex w-full justify-center">
                        <flux:button variant="subtle" icon="plus" size="xs" wire:click="nextPageLinks()">Mais Textos</flux:button>
                    </div>
                @endif
            </div>
            @endif

        </div>
        <flux:card class="w-2/6 overflow-y-scroll">
            <livewire:evidencia.details :id="$this->evidencia_id"/>
        </flux:card>
    </div>
    
    <div class="w-full h-1/12 flex flex-row-reverse items-center mt-2">
        <flux:button size="sm" icon:trailing="paper-airplane" :disabled="!$this->evidencia_id" wire:click="selectEvidencia()">Selecionar</flux:button>
    </div>
    
    <livewire:evidencia.upload/>
</flux:modal>