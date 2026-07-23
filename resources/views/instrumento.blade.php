<x-layouts::app>
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            <flux:icon.clipboard-document-list/>
            <flux:heading size="lg">Instrumentos de Avaliação</flux:heading>
        </div>
        <div class="items-center flex gap-2">
            <flux:input placeholder="Pesquise Instrumentos..." onchange="Livewire.dispatch('search', {s : this.value})" icon="magnifying-glass"/>

            <flux:modal.trigger name="create">
                <flux:button icon="plus">Novo</flux:button>
            </flux:modal.trigger>
        </div>
    </div>
    
    <livewire:instrumento.list />
    <livewire:instrumento.create />
    <livewire:instrumento.details />
    <livewire:instrumento.edit />
    <livewire:instrumento.remove />
</x-layouts::app>