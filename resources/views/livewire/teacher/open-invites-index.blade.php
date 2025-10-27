<flux:table class="w-full" :paginate="$this->invitations">
    <flux:table.columns>
        <flux:table.column sortable :sorted="$sortBy === 'email'" :direction="$sortDirection" wire:click="sort('email')">Email</flux:table.column>
        <flux:table.column>Role</flux:table.column>
        <flux:table.column></flux:table.column>
    </flux:table.columns>

    <flux:table.rows>
        @foreach($this->invitations() as $invitation)
            <livewire:teacher.open-invites-index-item :key="$invitation->id" :invitation="$invitation" />
        @endforeach
    </flux:table.rows>
</flux:table>
