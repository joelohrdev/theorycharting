<x-layouts.app :title="__('Open Invites')">
    <div class="flex items-center justify-between">
        <flux:heading size="xl">Open Invites</flux:heading>
        <livewire:teacher.invite />
    </div>
    <flux:separator variant="subtle" class="my-4" />
    <livewire:teacher.open-invites-index />
</x-layouts.app>
