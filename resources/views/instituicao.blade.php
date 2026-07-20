<x-layouts::app>
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            <flux:icon.academic-cap/>
            <flux:heading size="lg">Lista de Instituições</flux:heading>
        </div>
        <div class="items-center flex gap-2">
            <flux:input placeholder="Pesquise Instituições..." onchange="Livewire.dispatch('search', {s : this.value})" icon="magnifying-glass"/>

            <flux:modal.trigger name="create">
                <flux:button icon="plus">Novo</flux:button>
            </flux:modal.trigger>
        </div>
    </div>
    
    <livewire:instituicao.list />
    <livewire:instituicao.create />
    <livewire:instituicao.details />
    <livewire:instituicao.edit />
    <livewire:instituicao.remove />
</x-layouts::app>