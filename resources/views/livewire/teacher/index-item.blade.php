<flux:table.row>
    <flux:table.cell>
        {{ $user->name }}
        @if($user->is_admin)
            <flux:badge size="sm" color="lime" inset="top bottom">Admin</flux:badge>
        @elseif($user->email_verified_at)
            <flux:badge size="sm" color="yellow" inset="top bottom">Not Registered</flux:badge>
        @endif
    </flux:table.cell>
    <flux:table.cell>{{ $user->email }}</flux:table.cell>
    <flux:table.cell>{{ $this->studentCount() }}</flux:table.cell>
    <flux:table.cell align="end">
        <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom"></flux:button>
    </flux:table.cell>
</flux:table.row>
