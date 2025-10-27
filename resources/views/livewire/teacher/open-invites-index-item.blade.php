<flux:table.row>
    <flux:table.cell>
        {{ $invitation->email }}
    </flux:table.cell>
    <flux:table.cell>{{ $invitation->role }}</flux:table.cell>
    <flux:table.cell align="end">
        <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom"></flux:button>
    </flux:table.cell>
</flux:table.row>
