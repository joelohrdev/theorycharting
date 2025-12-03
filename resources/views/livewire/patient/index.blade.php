<flux:card>
    <div class="w-1/4 mb-4">
        <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Search patients..." />
    </div>
    <div class="overflow-x-auto">
        <flux:table :paginate="$this->patients">
            <flux:table.columns>
                <flux:table.column sticky sortable :sorted="$sortBy === 'name'" :direction="$sortDirection" wire:click="sort('name')" class="bg-white">Name</flux:table.column>
                <flux:table.column>Details</flux:table.column>
                <flux:table.column>Admission Info</flux:table.column>
                <flux:table.column>Diagnosis</flux:table.column>
                <flux:table.column>Diet & Activity</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse($this->patients() as $patient)
                    <flux:table.row :key="$patient->id">
                        <flux:table.cell sticky class="bg-white">
                            <a wire:navigate href="{{ route('patient.show', $patient) }}" class="text-sm font-medium text-gray-900 hover:underline">
                                {{ $patient->name }}
                                <div class="text-xs text-gray-500">{{ $patient->mrn }}</div>
                            </a>
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="text-xs text-gray-900">{{ $patient->gender }}, {{ \Carbon\Carbon::parse($patient->birth_date)->age }}y</div>
                            <div class="text-xs text-gray-500">DOB: {{ $patient->birth_date->format('m/d/Y') }}</div>
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex flex-col gap-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 w-fit">Rm {{ $patient->room }}</span>
                                <div class="text-xs text-gray-500">{{ $patient->admission_date->format('m/d/Y H:i') }}</div>
                                <div class="text-xs text-gray-500 font-medium">{{ $patient->attending_md }}</div>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="text-xs text-gray-900 line-clamp-2">{{ $patient->diagnosis }}</div>
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="text-xs text-gray-900">{{ $patient->diet_order }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">{{ $patient->activity_level }}</div>
                        </flux:table.cell>
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

</flux:card>
