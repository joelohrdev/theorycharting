<div>
    <div class="w-1/4 mb-4">
        <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Search patients..." />
    </div>
    <div class="overflow-x-auto">
        <flux:table :paginate="$this->patients">
            <flux:table.columns>
                <flux:table.column sticky sortable :sorted="$sortBy === 'name'" :direction="$sortDirection" wire:click="sort('name')" class="bg-white">Name</flux:table.column>
                <flux:table.column>Gender</flux:table.column>
                <flux:table.column>Age</flux:table.column>
                <flux:table.column>Birthdate</flux:table.column>
                <flux:table.column>MRN</flux:table.column>
                <flux:table.column>Room</flux:table.column>
                <flux:table.column>Admission Date</flux:table.column>
                <flux:table.column>Attending MD</flux:table.column>
                <flux:table.column>Diagnosis</flux:table.column>
                <flux:table.column>Diet Order</flux:table.column>
                <flux:table.column>Activity Level</flux:table.column>
                <flux:table.column>Procedure</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column>Isolation</flux:table.column>
                <flux:table.column>Unit</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse($this->patients() as $patient)
                    <flux:table.row :key="$patient->id">
                        <flux:table.cell sticky class="bg-white">
                            <a wire:navigate href="{{ route('patient.show', $patient) }}" class="hover:underline">
                                {{ $patient->name }}
                            </a>
                        </flux:table.cell>
                        <flux:table.cell>{{ $patient->gender }}</flux:table.cell>
                        <flux:table.cell>{{ \Carbon\Carbon::parse($patient->birth_date)->age }}</flux:table.cell>
                        <flux:table.cell>{{ $patient->birth_date->format('m/d/Y') }}</flux:table.cell>
                        <flux:table.cell>{{ $patient->mrn }}</flux:table.cell>
                        <flux:table.cell>{{ $patient->room }}</flux:table.cell>
                        <flux:table.cell>{{ $patient->admission_date->format('m/d/Y H:i') }}</flux:table.cell>
                        <flux:table.cell>{{ $patient->attending_md }}</flux:table.cell>
                        <flux:table.cell>{{ $patient->diagnosis }}</flux:table.cell>
                        <flux:table.cell>{{ $patient->diet_order }}</flux:table.cell>
                        <flux:table.cell>{{ $patient->activity_level }}</flux:table.cell>
                        <flux:table.cell>{{ $patient->procedure }}</flux:table.cell>
                        <flux:table.cell>{{ $patient->status }}</flux:table.cell>
                        <flux:table.cell>{{ $patient->isolation }}</flux:table.cell>
                        <flux:table.cell>{{ $patient->unit }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:button wire:navigate :href="route('patient.show', $patient)" size="xs" variant="primary" color="yellow">View</flux:button>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="14" class="text-center">There are no active patients</flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>

</div>
