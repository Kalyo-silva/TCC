<x-layouts::app>
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            <flux:icon.trophy/>
            <flux:heading size="lg">Lista de Avaliações</flux:heading>
        </div>
        <div class="items-center flex gap-2">
            <flux:input placeholder="Pesquise Avaliações..." onchange="Livewire.dispatch('search', {s : this.value})" icon="magnifying-glass"/>

            <flux:modal.trigger name="create">
                <flux:button icon="plus">Novo</flux:button>
            </flux:modal.trigger>
        </div>
    </div>
    
    <livewire:avaliacao.create />
    <livewire:avaliacao.list />

    <livewire:avaliacao.edit />
    <livewire:avaliacao.details />
    <livewire:avaliacao.remove />
</x-layouts::app>