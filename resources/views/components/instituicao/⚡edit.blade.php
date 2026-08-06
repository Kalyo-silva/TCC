<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\instituicao;
use App\Models\mantenedor;
use Livewire\WithFileUploads;
use Flux\Flux;

new class extends Component
{
    use WithFileUploads;

    public $id;
    public $logo;
    public $old_logo;
    public $nome;
    public $sigla;
    public $cidade;
    public $uf;
    public $bairro;
    public $cep;
    public $logradouro;
    public $mantenedor_id;

    #[On('DetailEdit')]
    public function getIdinstituicao($id){
        Flux::modal('details')->close();

        $this->id = $id;

        $instituicao = instituicao::with('mantenedor')->find($this->id);

        if ($instituicao){
            $this->nome = $instituicao->nome;
            $this->cidade = $instituicao->cidade;
            $this->uf = $instituicao->uf;
            $this->bairro = $instituicao->bairro;
            $this->cep = $instituicao->cep;
            $this->logradouro = $instituicao->logradouro;
            $this->mantenedor_id = $instituicao->mantenedor_id;
            $this->sigla = $instituicao->sigla;
            $this->old_logo = $instituicao->logo;
        }
    }
    
    public function save(){
        $validated = $this->validate([
            "nome" => "required",
            "mantenedor_id" => "integer",
            "logo" => "nullable|file|image|mimes:jpg,jpeg,png"
        ]);

        $instituicao = instituicao::find($this->id);

        $instituicao->mantenedor_id = $this->mantenedor_id;
        $instituicao->nome = $this->nome;
        $instituicao->cidade = $this->cidade;
        $instituicao->uf = $this->uf;
        $instituicao->bairro = $this->bairro;
        $instituicao->cep = $this->cep;
        $instituicao->logradouro = $this->logradouro;
        $instituicao->sigla = $this->sigla;

        if ($this->logo) {
            Storage::disk('public')->delete('img_instituicoes/'.$this->old_logo);
            $filename = date('YmdHis') . $this->logo->getClientOriginalName();
            Storage::disk('public')->putFileAs('img_instituicoes', $this->logo, $filename);
            $instituicao->logo = $filename;
        }

        if ($validated){
            try{
                if ($instituicao->save()){
                    $this->dispatch('postInsert');
                    $this->reset();
                    Flux::toast(variant : "success", text: 'Registro Alterado com sucesso!');
                    Flux::modal('edit')->close();
                    Flux::modal('details')->close();
                }
            }
            catch (Throwable $e){
                if ($e->getCode() == 23505){ 
                    Flux::toast(variant : "danger", heading: 'Falha ao alterar o registro...', text: "Esta instituição já está cadastrada no sistema.");
                }
                else{   
                    Flux::toast(variant : "danger", heading: 'Falha ao alterar o registro...', text : $e->getMessage());
                }
            }
        }
    }

    public function getMantenedores(){
        return mantenedor::orderby('nome', 'asc')->get(); 
    }
};
?>

<flux:modal name="edit">
    <div class="flex items-center gap-2">
        <flux:icon.plus/>
        <flux:heading size="">Editar Instituição</flux:heading>
    </div>
        <form wire:submit='save' class="flex flex-col gap-4 mt-4">
            <div class="flex items-center gap-4">
                <div class="w-27 h-24 rounded-lg border-zinc-600 overflow-hidden" x-on:click="$refs.logo.click()">
                    @if (!$this->logo)
                        <img class="w-full h-full object-cover" src="{{ asset('storage/img_instituicoes/'.$this->old_logo) }}">
                    @elseif ($this->logo && method_exists($this->logo, 'temporaryUrl'))
                        <img class="w-full h-full object-cover" src="{{ $this->logo->temporaryUrl() }}">
                    @else
                        <flux:icon.camera class="size-12"/>
                    @endif
                </div>
                <input type="file" x-ref="logo" name="logo" id="logo" class="sr-only" wire:model="logo" accept=".jpg, .jpeg, .png">
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
            <flux:input.group>
                <flux:button icon='building-library'/>
                <flux:select wire:model.live='mantenedor_id'>
                    <flux:select.option value="dummy" selected>Mantenedor...</flux:select.option>
                    @foreach ($this->getMantenedores() as $mant)
                        @if ($mant->id == $this->mantenedor_id)
                            <flux:select.option selected value="{{ $mant->id }}">{{$mant->nome}}</flux:select.option>
                        @else
                            <flux:select.option value="{{ $mant->id }}">{{$mant->nome}}</flux:select.option>
                        @endif
                    @endforeach
                </flux:select>
            </flux:input.group>
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





