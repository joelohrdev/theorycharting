<flux:navbar>
    <flux:navbar.item wire:navigate href="{{ route('student.index') }}" :current="request()->routeIs('student.index')">Active</flux:navbar.item>
    <flux:navbar.item wire:navigate href="{{ route('student.invites') }}" :current="request()->routeIs('student.invites')">Open Invites</flux:navbar.item>
    <flux:navbar.item wire:navigate href="{{ route('student.deleted') }}" :current="request()->routeIs('student.deleted')">Deleted</flux:navbar.item>
</flux:navbar>
