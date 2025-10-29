<flux:table.row>
    <flux:table.cell>
        {{ $user->name }}
    </flux:table.cell>
    <flux:table.cell>{{ $user->email }}</flux:table.cell>
    <flux:table.cell align="end">
        <flux:dropdown>
            <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom"></flux:button>

            <flux:menu>
                <flux:menu.item wire:click="restoreStudent" wire:confirm="Are you sure you want to restore {{ $user->name }}?" icon="arrow-path-rounded-square">Restore</flux:menu.item>
            </flux:menu>
        </flux:dropdown>
    </flux:table.cell>
</flux:table.row>
