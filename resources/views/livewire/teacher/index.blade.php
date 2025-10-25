<div>
    <flux:table class="w-full" :paginate="$this->teachers">
        <flux:table.columns>
            <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection" wire:click="sort('name')">Name</flux:table.column>
            <flux:table.column>Email</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach($this->teachers() as $teacher)
                <flux:table.row>
                    <flux:table.cell>
                        {{ $teacher->name }}
                        @if($teacher->is_admin)
                        <flux:badge size="sm" color="lime" inset="top bottom">Admin</flux:badge>
                        @endif
                    </flux:table.cell>
                    <flux:table.cell>{{ $teacher->email }}</flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
