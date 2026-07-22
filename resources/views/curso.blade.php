<x-layouts::app>
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            <flux:icon.book-open/>
            <flux:heading size="lg">Lista de Cursos</flux:heading>
        </div>
        <div class="items-center flex gap-2">
            <flux:input placeholder="Pesquise Cursos..." onchange="Livewire.dispatch('search', {s : this.value})" icon="magnifying-glass"/>

            <flux:modal.trigger name="create">
                <flux:button icon="plus">Novo</flux:button>
            </flux:modal.trigger>
        </div>
    </div>
    
    <livewire:curso.list />
    <livewire:curso.create />
    <livewire:curso.edit />
    <livewire:curso.remove />
</x-layouts::app>