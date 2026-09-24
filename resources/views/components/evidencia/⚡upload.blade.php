<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\evidencia;
use Flux\Flux;

new class extends Component
{
    use WithFileUploads;

    public $titulo;
    public $ano;
    public $tipo;

    public $file;
    public $link;
    public $texto;
 
    public function clearFields(){
        $this->file = Null;
        $this->link = Null;
        $this->texto = Null;
    }

    public function mount(){
        $this->ano = now()->year;
    }

    public function retornaExtensaoTipo($arquivo){
        if ($arquivo){
            if (in_array($arquivo->extension(), ['png', 'jpg','jpeg','gif','webp', 'svg'])){
                return 2;
            }
            else if (in_array($arquivo->extension(), ['mp4', 'mkv','avi','wmv','mov', 'webm'])){
                return 3;
            }
            else if (in_array($arquivo->extension(), ['mp3', 'wav','ogg'])){
                return 4;
            }
        }

        return 1;
    }

    public function close(){
        $this->reset();
        $this->ano = now()->year;

        Flux::modal('upload')->close();
    }

    public function save(){
        $evidencia = new evidencia();

        $evidencia->titulo = $this->titulo;
        $evidencia->ano = $this->ano;
        $evidencia->tipo = $this->tipo;

        $validated = $this->validate([
            "titulo" => "required",
            "ano" => "required|integer",
            "tipo" => "required|integer",
        ]);

        if ($validated){ 
            if ($this->tipo == 6){
                $evidencia->link = $this->link;

                if (!$evidencia->link){
                    Flux::toast(variant : "danger", heading: 'Falha ao cadastrar evidência...', text: "Nenhum texto foi informado.");
                    return false;
                }
            }
            else if ($this->tipo == 5){
                $evidencia->text = $this->texto;

                if (!$evidencia->text){
                    Flux::toast(variant : "danger", heading: 'Falha ao cadastrar evidência...', text: "Nenhum texto foi informado.");
                    return false;
                }
            }
            else{
                if ($this->file) {
                    $tipo_arquivo = $this->retornaExtensaoTipo($this->file);

                    $filename = date('YmdHis') . $this->file->getClientOriginalName();
                    Storage::disk('public')->putFileAs('evidencias', $this->file, $filename);
                    
                    $evidencia->file_name = $this->file->getClientOriginalName();
                    $evidencia->file_path = $filename;
                    $evidencia->tipo = $tipo_arquivo;

                } else{
                    Flux::toast(variant : "danger", heading: 'Falha ao cadastrar evidência...', text: "Nenhum arquivo foi informado.");
                    return false;
                }
            } 

            try{
                if ($evidencia->save()){
                    $this->dispatch('postInsert');
                    Flux::toast(variant : "success", text: 'Evidência cadastrada com sucesso!');
                    $this->close();
                }
                return true;
            }
            catch (Throwable $e){  
                Flux::toast(variant : "danger", heading: 'Falha ao cadastrar evidência...', text : $e->getMessage());
                return false;
            }
        }
    }
};
?>

<flux:modal name='upload' class="min-w-2xl flex flex-col gap-4">
    <div class="flex items-center gap-2">
        <flux:icon.paper-clip/>
        <flux:heading size="">Nova Evidência</flux:heading>
    </div>
    <form class="flex flex-col gap-4" wire:submit="save()">
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

        <flux:select wire:model.live="tipo" wire:change="clearFields()">
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
                            <flux:card class="h-64 rounde-lg flex flex-col items-center gap-2">
                                <video class="h-full object-cover rounded-lg border" controls src="{{ $this->file->temporaryUrl() }}"></video>
                                <div class="flex flex-col items-center">
                                    <flux:heading size="lg" class="underline">{{$this->file->getClientOriginalName()}}</flux:heading>
                                </div>
                            </flux:card>
                        @elseif ($this->tipo == 4)
                            <flux:card class="h-64 rounde-lg flex flex-col gap-1 items-center justify-center">
                                <audio src="{{ $this->file->temporaryUrl() }}" controls class="w-full rounded"></audio>
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
            <flux:button variant='primary' type='submit' icon:trailing="paper-airplane">Enviar</flux:button>
            <flux:button icon="x-circle" wire:click="close()">cancelar</flux:button>
        </div>
    </form>
</flux:modal>