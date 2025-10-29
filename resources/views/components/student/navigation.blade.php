<flux:navbar>
    <flux:navbar.item wire:navigate href="{{ route('student.index') }}">Active</flux:navbar.item>
    <flux:navbar.item wire:navigate href="{{ route('student.invites') }}">Open Invites</flux:navbar.item>
    <flux:navbar.item wire:navigate href="{{ route('student.deleted') }}">Deleted</flux:navbar.item>
</flux:navbar>
