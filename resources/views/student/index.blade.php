<x-layouts.app :title="__('Students')">
    <div class="mb-5">
        <flux:heading size="xl">Students</flux:heading>
    </div>
    <div class="flex items-center justify-between">
        <x-student.navigation />
        <livewire:student.invite />
    </div>
    <flux:separator class="mb-4" />
    <livewire:student.index />
</x-layouts.app>
