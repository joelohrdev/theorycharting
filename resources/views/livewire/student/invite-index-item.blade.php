<flux:table.row>
    <flux:table.cell>
        {{ $invitation->email }}
    </flux:table.cell>
    <flux:table.cell>{{ $invitation->created_at->format('F d, Y') }}</flux:table.cell>
    <flux:table.cell align="end">
        <flux:dropdown>
            <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom"></flux:button>

            <flux:menu>
                <flux:menu.item wire:click="resendInvitation" wire:confirm="Are you sure you want to resend this invitation?" icon="envelope">Resend Invitation</flux:menu.item>
                <flux:menu.item wire:click="delete" wire:confirm="Are you sure you want to delete this invitation?" variant="danger" icon="trash">Delete</flux:menu.item>
            </flux:menu>
        </flux:dropdown>
    </flux:table.cell>
</flux:table.row>
