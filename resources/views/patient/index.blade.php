<x-layouts.app :title="__('Patients')">
    <div class="flex justify-between items-center mb-5">
        <flux:heading size="xl">Patients</flux:heading>
        @can('importPatients')
            <div class="flex gap-4">
                <flux:button wire:navigate href="{{ route('patient.create') }}" size="sm" icon="plus">Create Patient</flux:button>
                <livewire:patient.import />
            </div>
        @endcan
    </div>
    <div class="flex items-center justify-between">
    </div>
    <flux:separator class="mb-4" />
    <livewire:patient.index />
</x-layouts.app>
