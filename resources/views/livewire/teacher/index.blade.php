<flux:table class="w-full" :paginate="$this->teachers">
    <flux:table.columns>
        <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection" wire:click="sort('name')">Name</flux:table.column>
        <flux:table.column>Email</flux:table.column>
        <flux:table.column>Students</flux:table.column>
        <flux:table.column></flux:table.column>
    </flux:table.columns>

    <flux:table.rows>
        @foreach($this->teachers() as $teacher)
            <livewire:teacher.index-item :key="$teacher->id" :user="$teacher" />
        @endforeach
    </flux:table.rows>
</flux:table>
