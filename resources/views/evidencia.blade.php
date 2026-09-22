<x-layouts::app>
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            <flux:icon.paper-clip/>
            <flux:heading size="lg">Lista de Evidências</flux:heading>
        </div>
        <div class="items-center flex gap-2">
            <flux:input placeholder="Pesquise Documentos..." onchange="Livewire.dispatch('search', {s : this.value})" icon="magnifying-glass"/>
        </div>
    </div>
</x-layouts::app>