<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl" class="!font-extrabold">Bem vindo, {{auth()->user()->name}}.</flux:heading>
        <livewire:dashboard.comparate />
    </div>
</x-layouts::app>
