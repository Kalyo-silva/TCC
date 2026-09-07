<?php

use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public $file;
};
?>

<flux:modal name='create' class="flex flex-col">
    <form action="px-4 py-8 mt-10">    
        @if (!$file)
            <label for="file">
                <div class="flex flex-col items-center justify-center">
                    <flux:icon.cloud-arrow-up class="size-20"/>
                    <flux:heading>Realizar Upload</flux:heading>
                </div>
            </label>
        @else
            <flux:input value="{{  $this->file->getClientOriginalName() }}" />
            
        @endif
        <flux:input type="file"  name="file" class="sr-only" wire:model="file" id="file"/>

        
    </form>
</flux:modal>