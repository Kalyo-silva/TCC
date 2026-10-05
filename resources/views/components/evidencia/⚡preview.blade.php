<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\evidencia;
use Flux\Flux;

new class extends Component
{
    public $evidencia;

    #[On('detailEvidencia')]
    public function getEvidencia($id){
        $this->evidencia = evidencia::find($id);

        Flux::modal('evidencia_anexada')->show();
    }
};
?>


<flux:modal name='evidencia_anexada' class="flex flex-col gap-4">
    <div class="flex items-center gap-2">
        <flux:icon.paper-clip/>
        <flux:heading size="">Visualizar da evidência</flux:heading>
    </div>
    @if ($this->evidencia)
        @if (in_array($this->evidencia->tipo, [1,2,3,4]))
            @if ($this->evidencia->tipo == 2)
                <img src="{{ asset('storage/evidencias/'.$this->evidencia->file_path) }}" class="border rounded-lg max-h-96">
            @elseif ($this->evidencia->tipo == 3)
                <video src="{{ asset('storage/evidencias/'.$this->evidencia->file_path) }}" class="border rounded-lg max-h-96" controls></video>
            @elseif ($this->evidencia->tipo == 4)
                <div class="rounded-lg overflow-hidden border">
                    <audio src="{{ asset('storage/evidencias/'.$this->evidencia->file_path) }}" controls></audio>
                </div>
            @endif

            <div class="flex items-center justify-between">
                <flux:card class="px-4 py-2 rounded">
                    <div class="flex items-center gap-2">
                        @if ($this->evidencia->tipo == 1)
                            <flux:icon.document class="size-6" />
                        @elseif ($this->evidencia->tipo == 2)
                            <flux:icon.photo class="size-6" />
                        @elseif ($this->evidencia->tipo == 3)
                            <flux:icon.video-camera class="size-6" />
                        @elseif ($this->evidencia->tipo == 4)
                            <flux:icon.speaker-wave class="size-6" />
                        @elseif ($this->evidencia->tipo == 5)
                            <flux:icon.book-open class="size-6" />
                        @elseif ($this->evidencia->tipo == 6)
                            <flux:icon.paper-clip class="size-6" />
                        @endif
                        <flux:heading class="underline">{{ $this->evidencia->titulo }}</flux:headin>
                    </div>
                </flux:card>
                <a href="{{ asset('storage/evidencias/'.$this->evidencia->file_path) }}" download="{{ $this->evidencia->file_name }}"><flux:button icon="arrow-down-tray"></flux:button></a>
            </div>
        @elseif ($this->evidencia->tipo == 5)
            <flux:card class="w-full border rounded-lg cursor-pointer flex flex-col justify-start items-center overflow-hidden p-0"> 
                <div class="w-full px-4 py-2 flex items-center gap-2">
                    <flux:icon.book-open class="size-6"/>
                    <flux:heading class="underline truncate">{{$this->evidencia->titulo}}</flux:heading>
                </div>
                <flux:textarea readonly class="h-80">{{ $this->evidencia->text }}</flux:textarea>
            </flux:card>  
        @elseif ($this->evidencia->tipo == 6)
            <flux:card class="p-0 overflow-hidden border cursor-pointer w-full flex flex-col items-center">   
                <div class="flex w-full px-4 py-2 items-center gap-2 border-b">
                    <flux:icon.paper-clip class="size-6"/>
                    <flux:text class="w-full">{{$this->evidencia->titulo}}</flux:text> 
                </div>
                <a href="{{$this->evidencia->link}}" class="w-full text-center  " target="_blank"><flux:heading class="underline truncate px-6 py-4 bg-accent-foreground w-full">{{$this->evidencia->link}}</flux:heading></a>
            </flux:card>
        @endif
    @endif
</flux:modal>