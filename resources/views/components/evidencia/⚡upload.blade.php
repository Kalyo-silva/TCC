<?php

use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public $titulo;
    public $ano;
    public $tipo;

    public $file;
    public $link;
    public $texto;
 
    public function changeTipo(){
        $this->file = Null;
        $this->link = Null;
        $this->texto = Null;
    }

    public function mount(){
        $this->ano = now()->year;
    }
};
?>

<flux:modal name='upload' class="min-w-2xl flex flex-col gap-4">
    <div class="flex items-center gap-2">
        <flux:icon.paper-clip/>
        <flux:heading size="">Nova Evidência</flux:heading>
    </div>
    <form class="flex flex-col gap-4">
        <div class="flex items-center gap-4">
            <flux:button.group class="w-full">
                <div class="w-4/5">
                    <flux:input placeholder="Titulo..." wire:model='titulo'/>
                </div>
                <div class="w-1/5">
                    <flux:input placeholder="Ano..." wire:model='ano' type="number"/>
                </div>
            </flux:button.group>
        </div>

        <flux:select wire:model.live="tipo" wire:change="changeTipo()">
            <flux:select.option value='dummy'>Tipo...</flux:select.option>
            <flux:select.option value='1'>Documento</flux:select.option>
            <flux:select.option value='2'>Imagem</flux:select.option>
            <flux:select.option value='3'>Vídeo</flux:select.option>
            <flux:select.option value='4'>Áudio</flux:select.option>
            <flux:select.option value='5'>Texto</flux:select.option>
            <flux:select.option value='6'>Link</flux:select.option>
        </flux:select>

        @if ($this->tipo == '6')
            <flux:input wire:model='link' placeholder="Link..."/>
        @elseif ($this->tipo == '5')
            <flux:textarea type="text" wire:model='texto' placeholder="Texto..." rows="20"> </flux:textarea>
        @elseif ($this->tipo !="dummy" && $this->tipo)
            <div>
                <label for="file">
                    @if ($this->file && method_exists($this->file, 'temporaryUrl'))
                        @if ($this->tipo == 1)
                            <flux:card class="h-64 rounde-lg flex flex-col gap-1 items-center justify-center">
                                <flux:icon.document-text class="size-16"/>
                                <div class="flex flex-col items-center">
                                    <flux:heading size="lg" class="underline">{{$this->file->getClientOriginalName()}}</flux:heading>
                                </div>
                            </flux:card>
                        @elseif ($this->tipo == 2)
                            <flux:card class="h-64 rounde-lg flex flex-col items-center gap-2">
                                <img class="h-full object-cover rounded-lg border" src="{{ $this->file->temporaryUrl() }}">
                                <div class="flex flex-col items-center">
                                    <flux:heading size="lg" class="underline">{{$this->file->getClientOriginalName()}}</flux:heading>
                                </div>
                            </flux:card>
                        @elseif ($this->tipo == 3)
                            <flux:card class="h-64 rounde-lg flex flex-col gap-1 items-center justify-center">
                                <flux:icon.video-camera class="size-16"/>
                                <div class="flex flex-col items-center">
                                    <flux:heading size="lg" class="underline">{{$this->file->getClientOriginalName()}}</flux:heading>
                                </div>
                            </flux:card>
                        @endif
                    @else
                        <flux:card class="h-64 rounde-lg flex flex-col gap-1 items-center justify-center">
                            <flux:icon.cloud-arrow-up class="size-16"/>
                            <flux:heading>selecione um arquivo...</flux:heading>
                        </flux:card>    
                    @endif
                </label>
                <flux:input type="file" name="file" id="file" wire:model='file' class="hidden"/>
            </div>
        @endif

        <div class="flex flex-row-reverse items-center gap-2">
            <flux:button variant='primary' icon:trailing="paper-airplane">Enviar</flux:button>
            <flux:button icon="x-circle">cancelar</flux:button>
        </div>
    </form>
</flux:modal>