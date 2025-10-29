<flux:table class="w-full" :paginate="$this->invites">
    <flux:table.columns>
        <flux:table.column sortable :sorted="$sortBy === 'email'" :direction="$sortDirection" wire:click="sort('email')">Email</flux:table.column>
        <flux:table.column>Sent</flux:table.column>
        <flux:table.column></flux:table.column>
    </flux:table.columns>

    <flux:table.rows>
        @forelse($this->invites() as $invitation)
            <livewire:student.invite-index-item :key="$invitation->id" :$invitation />
        @empty
            <flux:table.row>
                <flux:table.cell colspan="3" class="text-center">There are no open invitations</flux:table.cell>
            </flux:table.row>
        @endforelse
    </flux:table.rows>
</flux:table>
