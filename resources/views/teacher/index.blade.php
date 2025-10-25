<x-layouts.app :title="__('Teachers')">
    <div class="flex items-center justify-between">
        <flux:heading size="xl">Teachers</flux:heading>
        <flux:button size="sm" variant="primary">Add Teacher</flux:button>
    </div>
    <flux:separator variant="subtle" class="my-4" />
    <livewire:teacher.index />
</x-layouts.app>
