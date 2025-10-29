<flux:table class="w-full" :paginate="$this->deletedStudents">
    <flux:table.columns>
        <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection" wire:click="sort('name')">Name</flux:table.column>
        <flux:table.column>Email</flux:table.column>
        <flux:table.column></flux:table.column>
    </flux:table.columns>

    <flux:table.rows>
        @forelse($this->deletedStudents() as $student)
            <livewire:student.delete-index-item :key="$student->id" :user="$student" />
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="3" class="text-center">There are no deleted students</flux:table.cell>
                </flux:table.row>
            @endforelse
    </flux:table.rows>
</flux:table>
