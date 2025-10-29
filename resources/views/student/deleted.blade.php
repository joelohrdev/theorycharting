<x-layouts.app :title="__('Deleted Students')">
    <div class="mb-5">
        <flux:heading size="xl">Students</flux:heading>
    </div>
    <div class="flex items-center justify-between">
        <x-student.navigation />
    </div>
    <flux:separator class="mb-4" />
    <livewire:student.delete-index />
</x-layouts.app>
