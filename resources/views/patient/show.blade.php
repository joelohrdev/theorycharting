<x-layouts.app :title="$patient->name">
    <div class="flex justify-between items-center mb-5">
        <flux:heading size="xl">{{ $patient->name }}</flux:heading>
        <flux:button wire:navigate :href="route('patient.index')" size="sm" variant="ghost" icon="arrow-left">Back</flux:button>
    </div>
    <flux:separator class="mb-4" />
    <div class="grid grid-cols-4 gap-4 space-y-2">
        <flux:card class="flex flex-col gap-2 font-mono text-xs">
            <div>
                <span class="font-bold">MRN:</span>
                {{ $patient->mrn }}
            </div>
            <div>
                <span class="font-bold">Age:</span>
                {{ \Carbon\Carbon::parse($patient->birth_date)->age }}
            </div>
            <div>
                <span class="font-bold">DOB:</span>
                {{ $patient->birth_date->format('m/d/Y') }}
            </div>
            <div>
                <span class="font-bold">Sex:</span>
                {{ $patient->gender }}
            </div>
            <div>
                <span class="font-bold">Room:</span>
                {{ $patient->room }}
            </div>
            <div>
                <span class="font-bold">Admission Date:</span>
                {{ $patient->admission_date->format('m/d/Y') }}
            </div>
        </flux:card>
        <flux:card class="flex flex-col gap-2 font-mono text-xs">
            <div>
                <span class="font-bold">Diagnosis:</span>
                {{ $patient->diagnosis }}
            </div>
            <div>
                <span class="font-bold">Diet Order:</span>
                {{ $patient->diet_order }}
            </div>
            <div>
                <span class="font-bold">Activity Level:</span>
                {{ $patient->activity_level }}
            </div>
            <div>
                <span class="font-bold">Procedure:</span>
                {{ $patient->procedure }}
            </div>
        </flux:card>
        <flux:card class="flex flex-col gap-2 font-mono text-xs">
            <div>
                <span class="font-bold">Status:</span>
                {{ $patient->status }}
            </div>
            <div>
                <span class="font-bold">Isolation:</span>
                {{ $patient->isolation }}
            </div>
            <div>
                <span class="font-bold">Unit:</span>
                {{ $patient->unit }}
            </div>
            <div>
                <span class="font-bold">Procedure:</span>
                {{ $patient->procedure }}
            </div>
        </flux:card>
        <flux:card class="flex flex-col gap-2 font-mono text-xs">
            <div>
                <span class="font-bold">Attending MD:</span>
                {{ $patient->attending_md }}
            </div>
            <div>
                <span class="font-bold">Isolation:</span>
                {{ $patient->isolation }}
            </div>
            <div>
                <span class="font-bold">Unit:</span>
                {{ $patient->unit }}
            </div>
            <div>
                <span class="font-bold">Procedure:</span>
                {{ $patient->procedure }}
            </div>
        </flux:card>
    </div>

    <flux:separator class="my-6" />

    <div x-data="{
        activeTab: 'vital-signs',
        columns: [
            { time: '1100', data: {} },
            { time: '1200', data: {} },
            { time: '1300', data: {} },
            { time: '1400', data: {} }
        ],
        addColumn() {
            const lastTime = this.columns[this.columns.length - 1].time;
            const nextTime = String(parseInt(lastTime) + 100).padStart(4, '0');
            this.columns.push({ time: nextTime, data: {} });
        },
        removeColumn(index) {
            if (this.columns.length > 1) {
                this.columns.splice(index, 1);
            }
        }
    }">
        <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-1 inline-flex gap-1 mb-4">
            <button @click="activeTab = 'vital-signs'"
                    :class="activeTab === 'vital-signs' ? 'bg-white dark:bg-gray-700 shadow' : 'text-gray-600 dark:text-gray-400'"
                    class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                Vital Signs
            </button>
            <button @click="activeTab = 'intake-output'"
                    :class="activeTab === 'intake-output' ? 'bg-white dark:bg-gray-700 shadow' : 'text-gray-600 dark:text-gray-400'"
                    class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                Intake/Output
            </button>
            <button @click="activeTab = 'restraints'"
                    :class="activeTab === 'restraints' ? 'bg-white dark:bg-gray-700 shadow' : 'text-gray-600 dark:text-gray-400'"
                    class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                ADLs
            </button>
        </div>

        <div x-show="activeTab === 'vital-signs'">
            <livewire:patient.vitals-form :$patient />
        </div>

        <div x-show="activeTab === 'intake-output'" class="text-gray-500 dark:text-gray-400 py-8 text-center">
            <livewire:patient.intake-output :$patient />
        </div>

        <div x-show="activeTab === 'restraints'" class="text-gray-500 dark:text-gray-400 py-8 text-center">
            <livewire:patient.adl :$patient />
        </div>
    </div>
</x-layouts.app>
