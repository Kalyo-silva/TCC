<x-layouts::app>
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            <flux:icon.building-library/>
            <flux:heading size="lg">Lista de Mantenedores</flux:heading>
        </div>
        <div class="items-center flex gap-2">
            <flux:input placeholder="Pesquise Mantenedores..." onchange="Livewire.dispatch('search', {s : this.value})" icon="magnifying-glass"/>

            <flux:modal.trigger name="create">
                <flux:button icon="plus">Novo</flux:button>
            </flux:modal.trigger>
        </div>
    </div>
    
    <livewire:mantenedor.list />
    <livewire:mantenedor.create />
    <livewire:mantenedor.details />
    <livewire:mantenedor.edit />
    <livewire:mantenedor.remove />
</x-layouts::app>