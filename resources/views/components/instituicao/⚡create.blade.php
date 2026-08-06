<?php

use Livewire\Component;
use Flux\Flux;
use App\Models\instituicao;
use App\Models\mantenedor;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public $mantenedores = [];

    public $mantenedor_id;
    public $nome;
    public $cidade;
    public $uf;
    public $bairro;
    public $cep;
    public $logradouro;
    public $logo;
    public $sigla;

    public function save(){
        $validated = $this->validate([
            "nome" => "required",
            "mantenedor_id" => "integer",
            "logo" => "nullable|file|image|mimes:jpg,jpeg,png"
        ]);

        $instituicao = new instituicao();

        $instituicao->mantenedor_id = $this->mantenedor_id;
        $instituicao->nome = $this->nome;
        $instituicao->cidade = $this->cidade;
        $instituicao->uf = $this->uf;
        $instituicao->bairro = $this->bairro;
        $instituicao->cep = $this->cep;
        $instituicao->logradouro = $this->logradouro;
        $instituicao->sigla = $this->sigla;

        if ($this->logo) {
            $filename = date('YmdHis') . $this->logo->getClientOriginalName();
            Storage::disk('public')->putFileAs('img_instituicoes', $this->logo, $filename);
            $instituicao->logo = $filename;
        }

        if ($validated){
            try{
                if ($instituicao->save()){
                    $this->dispatch('postInsert');
                    $this->reset();
                    Flux::toast(variant : "success", text: 'Registro criado com sucesso!');
                    Flux::modal('create')->close();
                }
            }
            catch (Throwable $e){
                if ($e->getCode() == 23505){ 
                    Flux::toast(variant : "danger", heading: 'Falha ao criar o registro...', text: "Esta instituição já está cadastrada no sistema.");
                }
                else{   
                    Flux::toast(variant : "danger", heading: 'Falha ao criar o registro...', text : $e->getMessage());
                }
            }
        }
    }

    public function getMantenedores(){
        return mantenedor::orderby('nome', 'asc')->get(); 
    }
};
?>

<flux:modal name="create">
    <div class="flex items-center gap-2">
        <flux:icon.plus/>
        <flux:heading size="">Nova Instituição</flux:heading>
    </div>
        <form wire:submit='save' class="flex flex-col gap-4 mt-4">
            <div class="flex items-center gap-4">
                <label for="logo" class="h-24 w-24">
                    <div class="cursor-pointer h-full rounded-lg border border-zinc-600 w-full bg-zinc-700 items-center justify-center flex overflow-hidden">
                        @if ($this->logo && method_exists($this->logo, 'temporaryUrl'))
                            <img class="w-full h-full object-cover" src="{{ $this->logo->temporaryUrl() }}">
                        @else
                            <flux:icon.camera class="size-12"/>
                        @endif
                    </div>
                </label>
                <input type="file" name="logo" id="logo" wire:model="logo" hidden accept=".jpg, .jpeg, .png">
                <div class="flex flex-col gap-4">
                    <div class="flex gap-4">
                        <div class="w-6/10">
                            <flux:input placeholder="Nome..." wire:model='nome'/>
                        </div>
                        <div class="w-4/10">
                            <flux:input placeholder="Sigla..." wire:model='sigla'/>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-8/10">
                            <flux:input placeholder="Cidade..." wire:model="cidade"/>
                        </div>
                        <div class="w-2/10">
                            <flux:input placeholder="UF..." wire:model="uf" maxlength="2"/>
                        </div>
                    </div>
                </div>
            </div>
            <flux:select wire:model.live='mantenedor_id'>
                <flux:select.option value="dummy" selected>Mantenedor...</flux:select.option>
                @foreach ($this->getMantenedores() as $mant)
                    <flux:select.option value="{{ $mant->id }}">{{$mant->nome}}</flux:select.option>
                @endforeach
            </flux:select>
            <div class="flex gap-4">
                <div class="w-7/10">
                    <flux:input placeholder="Bairro..." wire:model="bairro"/>
                </div>
                <div class="w-3/10">
                    <flux:input placeholder="CEP..." mask="99999-999" wire:model="cep"/>
                </div>
            </div>
            <flux:input placeholder="Logradouro..." wire:model="logradouro"/>
            <flux:button type="submit" class="mt-4" variant="primary">Salvar</flux:button>
        </form>
</flux:modal>



