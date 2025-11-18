<x-layouts.app :title="$patient->name">
    <div class="flex justify-between items-center mb-5">
        <flux:heading size="xl">{{ $patient->name }}</flux:heading>
        <flux:button wire:navigate :href="route('patient.index')" size="sm" variant="ghost" icon="arrow-left">Back</flux:button>
    </div>
    <flux:separator class="mb-4" />
    <div class="space-y-2">
        <div class=" flex gap-4 font-mono text-xs">
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
                {{ $patient->birth_date->format('m/d/Y') }} {{ $patient->gender }}
            </div>
            <div>
                <span class="font-bold">Room:</span>
                {{ $patient->room }}
            </div>
            <div>
                <span class="font-bold">Admission Date:</span>
                {{ $patient->admission_date->format('m/d/Y') }}
            </div>
        </div>
        <div class=" flex gap-4 font-mono text-xs">
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
        </div>
        <div class=" flex gap-4 font-mono text-xs">
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
        </div>
        <div class=" flex gap-4 font-mono text-xs">
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
        </div>
    </div>
</x-layouts.app>
