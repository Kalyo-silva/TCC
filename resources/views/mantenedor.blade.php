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

    <flux:modal name="create">
        <div class="flex items-center gap-2">
            <flux:icon.plus/>
            <flux:heading size="">Novo Mantenedor</flux:heading>
        </div>

        <livewire:mantenedor.create />
    </flux:modal>

    
    <flux:modal name="details">
        <div class="flex items-center gap-2">
            <flux:icon.information-circle/>
            <flux:heading size="">Detalhe do Mantenedor</flux:heading>
        </div>
        <livewire:mantenedor.details />
    </flux:modal>
    
    <livewire:mantenedor.edit />
    <livewire:mantenedor.remove />
</x-layouts::app>