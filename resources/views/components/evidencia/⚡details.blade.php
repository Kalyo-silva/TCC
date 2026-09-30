<?php

use Livewire\Component;
use Livewire\Attributes\Reactive;
use App\Models\evidencia;

new class extends Component
{
    #[Reactive]
    public $id;

    private $tipos = ['Documento', 'Imagem', 'Vídeo', 'Áudio', 'Texto', 'Link'];

    private $evidencia;

    public function updatedId($value){
        $this->evidencia = evidencia::find($value);
    }

    public function mount(){
        $this->evidencia = evidencia::find($this->id);
    }
};
?>

<div class="flex flex-col gap-4">
    @if ($this->id)
        <flux:badge icon="information-circle" rounded class="w-fit">Preview</flux:badge>
        
        @if ($this->evidencia->tipo == 1)
            <flux:card class="p-0 overflow-hidden border cursor-pointer w-full flex flex-col items-center">   
                <div class="flex w-full px-4 py-2 items-center gap-2">
                    <flux:icon.document class="size-6"/>
                    <flux:text class="w-full">{{$this->evidencia->titulo}}</flux:text> 
                </div>
                <div class="flex flex-col h-40 bg-accent-foreground w-full gap-2 items-center justify-center">
                    <flux:icon.document class="size-16"/>
                    <flux:text>Preview não disponível</flux:text>
                </div>
            </flux:card>
        @elseif($this->evidencia->tipo == 2)
            <flux:card class="p-0 overflow-hidden border cursor-pointer w-full flex flex-col items-center">   
                <div class="flex w-full px-4 py-2 items-center gap-2">
                    <flux:icon.photo class="size-6"/>
                    <flux:text class="w-full">{{$this->evidencia->titulo}}</flux:text> 
                </div>
                <img src="{{ asset('storage/evidencias/'.$this->evidencia->file_path) }}" class="w-full max-h-80 object-contain bg-black ">    
            </flux:card>
        @elseif($this->evidencia->tipo == 3)
            <flux:card class="p-0 overflow-hidden border cursor-pointer w-full flex flex-col items-center">   
                <div class="flex w-full px-4 py-2 items-center gap-2">
                    <flux:icon.video-camera class="size-6"/>
                    <flux:text class="w-full">{{$this->evidencia->titulo}}</flux:text> 
                </div>
                <video src="{{ asset('storage/evidencias/'.$this->evidencia->file_path) }}" controls class="w-full max-h-80 object-contain bg-black"> </video>  
            </flux:card> 
        @elseif($this->evidencia->tipo == 4)
            <flux:card class="p-0 overflow-hidden border cursor-pointer w-full flex flex-col items-center">   
                <div class="flex w-full px-4 py-2 items-center gap-2">
                    <flux:icon.musical-note class="size-6"/>
                    <flux:text class="w-full">{{$this->evidencia->titulo}}</flux:text> 
                </div>

                <audio src="{{ asset('storage/evidencias/'.$this->evidencia->file_path) }}" controls class="w-full"> </audio>   
            </flux:card>
        @elseif($this->evidencia->tipo == 5)
            <flux:card class="max-h-80 w-full border rounded-lg cursor-pointer flex flex-col justify-start items-center overflow-hidden p-0"> 
                <div class="w-full px-4 py-2 flex items-center gap-2">
                    <flux:icon.book-open class="size-6"/>
                    <flux:heading class="underline truncate">{{$this->evidencia->titulo}}</flux:heading>
                </div>
                <flux:textarea readonly class="h-80">{{ $this->evidencia->text }}</flux:textarea>
            </flux:card>    
        @elseif($this->evidencia->tipo == 6)
            <flux:card class="p-0 overflow-hidden border cursor-pointer w-full flex flex-col items-center">   
                <div class="flex w-full px-4 py-2 items-center gap-2 border-b">
                    <flux:icon.paper-clip class="size-6"/>
                    <flux:text class="w-full">{{$this->evidencia->titulo}}</flux:text> 
                </div>
                <a href="{{$this->evidencia->link}}" class="w-full text-center  " target="_blank"><flux:heading class="underline truncate px-6 py-4 bg-accent-foreground w-full">{{$this->evidencia->link}}</flux:heading></a>
            </flux:card>
        @endif

        <flux:badge icon="" rounded class="w-fit">Tipo de Evidência</flux:badge>
        <flux:input readonly value="{{ $this->tipos[$this->evidencia->tipo - 1]}}"></flux:input>

        @if (in_array($this->evidencia->tipo, [1,2,3,4]))

        <flux:badge rounded class="w-fit">Arquivo</flux:badge>
        <flux:button.group>
            <flux:input readonly value="{{$this->evidencia->file_name}}" class="truncate"></flux:input>
            <a href="{{ asset('storage/evidencias/'.$this->evidencia->file_path) }}" download="{{ $this->evidencia->file_name }}"><flux:button icon="arrow-down-tray"></flux:button></a>
        </flux:button.group>

        @endif

        <flux:badge icon="" rounded class="w-fit">Cadastrado em</flux:badge>
        <flux:button.group>
            <flux:button icon="calendar-days"></flux:button>
            <flux:input value="{{$this->evidencia->created_at->format('d/m/Y')}}" readonly/>
        </flux:button.group>
    @endif
</div>