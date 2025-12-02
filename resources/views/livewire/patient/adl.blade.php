@php
$hasOtherSelected = function($field, $adls, $formField = null) {
    $hasInSaved = collect($adls)->filter(fn($adl) => !empty($adl->$field) && in_array('other', $adl->$field))->count() > 0;
    $hasInForm = isset($formField) && in_array('other', $formField);
    return $hasInSaved || $hasInForm;
};
@endphp
<div x-data="{
    showNewColumn: false,
    addColumn() {
        this.showNewColumn = true;
        setTimeout(() => {
            const wrapper = document.getElementById('adl-table-wrapper');
            if (wrapper) wrapper.scrollTo({ left: 999999, behavior: 'smooth' });
        }, 200);
    },
    cancelNewColumn() {
        this.showNewColumn = false;
    }
}" wire:key="adl-{{ $patient->id }}">
<div id="adl-table-wrapper" style="overflow-x: auto; margin-bottom: 100px;">
<table class="w-full border-collapse text-sm">
    <colgroup>
        <col style="width: 256px; min-width: 256px; max-width: 256px;">
        @foreach($this->adls as $adl)
            <col>
        @endforeach
        <col x-show="showNewColumn">
        <col style="width: 160px; min-width: 160px; max-width: 160px;">
    </colgroup>
    <thead>
    <tr class="border-b border-gray-200 dark:border-gray-700">
        <th class="text-left py-3 px-4 bg-gray-50 dark:bg-gray-900 sticky left-0 z-20 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)]" style="width: 256px; min-width: 256px; max-width: 256px;">
            <span class="font-semibold text-gray-900 dark:text-gray-100">ADLs</span>
        </th>
        @foreach($this->adls as $adl)
            <th class="text-center group relative py-3 px-2 bg-gray-50 dark:bg-gray-900 border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:tooltip>
                    <span @class(['font-semibold text-gray-900 dark:text-gray-100', 'line-through' => $adl->trashed()])>
                        {{ $adl->created_at->format('H:i') }}
                    </span>
                    <x-slot:content>
                        <p>{{ $adl->created_at->format('F d, Y H:i') }}<br>{{ $adl->user->name }}</p>
                        @if($adl->trashed())
                        <p class="mt-3">Deleted:<br> {{ $adl->deleted_at->format('F d, Y H:i') }}<br>{{ $adl->deletedBy->name }}</p>
                        @endif
                    </x-slot:content>
                </flux:tooltip>
                @if(!$adl->trashed())
                <div class="absolute right-2 inset-y-0 flex items-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <livewire:forms.delete :model="$adl" />
                </div>
                @endif
                @if($adl->trashed())
                    <div class="absolute right-2 inset-y-0 flex items-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <livewire:forms.restore :model="$adl" />
                    </div>
                @endif
            </th>
        @endforeach
        <template x-if="showNewColumn">
            <th class="text-center py-3 px-2 bg-gray-50 dark:bg-gray-900 border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span class="font-semibold text-gray-900 dark:text-gray-100">New</span>
            </th>
        </template>
        <th class="text-left py-3 px-4 bg-gray-50 dark:bg-gray-900 sticky right-0 z-20" style="width: 160px; min-width: 160px; max-width: 160px;">
            <template x-if="!showNewColumn">
                <flux:button
                    @click="addColumn()"
                    variant="primary"
                    size="sm"
                    icon="plus"
                    color="yellow"
                >Add Column</flux:button>
            </template>
            <template x-if="showNewColumn">
                <flux:button
                    @click="cancelNewColumn()"
                    variant="ghost"
                    size="sm"
                >Cancel</flux:button>
            </template>
        </th>
    </tr>
    </thead>
    <tbody>

    {{-- Bathing --}}
    @include('livewire.patient.adl-partials.multi-select-field', [
        'field' => 'bathing',
        'formField' => 'bathing',
        'label' => 'Bathing',
        'enum' => \App\Enums\Bathing::class,
        'borderClass' => 'border-b'
    ])

    {{-- Bathing Level of Assist --}}
    @include('livewire.patient.adl-partials.multi-select-field', [
        'field' => 'bathing_level_of_assist',
        'formField' => 'bathingLevelOfAssist',
        'label' => 'Level of Assist',
        'enum' => \App\Enums\LevelOfAssist::class,
        'borderClass' => 'border-b'
    ])

    {{-- Oral Care --}}
    @include('livewire.patient.adl-partials.multi-select-field', [
        'field' => 'oral_care',
        'formField' => 'oralCare',
        'label' => 'Oral Care',
        'enum' => \App\Enums\OralCare::class,
        'borderClass' => 'border-b'
    ])

    {{-- Oral Care Level of Assist --}}
    @include('livewire.patient.adl-partials.multi-select-field', [
        'field' => 'oral_care_level_of_assist',
        'formField' => 'oralCareLevelOfAssist',
        'label' => 'Level of Assist',
        'enum' => \App\Enums\LevelOfAssist::class,
        'borderClass' => 'border-b'
    ])

    {{-- Linen Change --}}
    @include('livewire.patient.adl-partials.multi-select-field', [
        'field' => 'linen_change',
        'formField' => 'linenChange',
        'label' => 'Linen Change',
        'enum' => \App\Enums\LinenChange::class,
        'borderClass' => 'border-b-4'
    ])

    <!-- Grooming Section -->
    <tr class="bg-gray-50 dark:bg-gray-900/50">
        <td class="text-left py-3 px-4 font-semibold text-gray-900 dark:text-gray-100 sticky left-0 z-10 bg-gray-50 dark:bg-gray-900/50 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700" style="width: 256px; min-width: 256px; max-width: 256px;">Grooming</td>
        @foreach($this->adls as $adl)
            <td class="bg-gray-50 dark:bg-gray-900/50 border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
        @endforeach
        <template x-if="showNewColumn"><td class="text-left bg-gray-50 dark:bg-gray-900/50 border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td></template>
        <td class="text-left bg-gray-50 dark:bg-gray-900/50 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    {{-- Hair --}}
    @include('livewire.patient.adl-partials.multi-select-field', [
        'field' => 'hair',
        'formField' => 'hair',
        'label' => 'Hair',
        'enum' => \App\Enums\Hair::class,
        'borderClass' => 'border-b'
    ])

    {{-- Shave --}}
    @include('livewire.patient.adl-partials.multi-select-field', [
        'field' => 'shave',
        'formField' => 'shave',
        'label' => 'Shave',
        'enum' => \App\Enums\Shave::class,
        'borderClass' => 'border-b'
    ])

    {{-- Deodorant --}}
    @include('livewire.patient.adl-partials.multi-select-field', [
        'field' => 'deodorant',
        'formField' => 'deodorant',
        'label' => 'Deodorant',
        'enum' => \App\Enums\Deodorant::class,
        'borderClass' => 'border-b'
    ])

    {{-- Nail Care --}}
    @include('livewire.patient.adl-partials.multi-select-field', [
        'field' => 'nail_care',
        'formField' => 'nailCare',
        'label' => 'Nail Care',
        'enum' => \App\Enums\NailCare::class,
        'borderClass' => 'border-b'
    ])

    {{-- Skin --}}
    @include('livewire.patient.adl-partials.multi-select-field', [
        'field' => 'skin',
        'formField' => 'skin',
        'label' => 'Skin',
        'enum' => \App\Enums\Skin::class,
        'borderClass' => 'border-b'
    ])

    {{-- Observations --}}
    <tr class="border-b-4 border-gray-200 dark:border-gray-700">
        <td class="text-left py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700" style="width: 256px; min-width: 256px; max-width: 256px;">Observations</td>
        @foreach($this->adls as $adl)
            <td class="text-left py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-gray-900 dark:text-gray-100 text-xs', 'line-through' => $adl->trashed()])>{{ $adl->observations ?? '-' }}</span>
            </td>
        @endforeach
        <template x-if="showNewColumn">
            <td class="text-left py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:textarea wire:model="form.observations" placeholder="Observations..." rows="4" required size="sm" />
            </td>
        </template>
        <td class="text-left bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <!-- Mobility Section -->
    <tr class="bg-gray-50 dark:bg-gray-900/50">
        <td class="text-left py-3 px-4 font-semibold text-gray-900 dark:text-gray-100 sticky left-0 z-10 bg-gray-50 dark:bg-gray-900/50 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700" style="width: 256px; min-width: 256px; max-width: 256px;">Activity & Mobility</td>
        @foreach($this->adls as $adl)
            <td class="text-left bg-gray-50 dark:bg-gray-900/50 border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
        @endforeach
        <template x-if="showNewColumn"><td class="text-left bg-gray-50 dark:bg-gray-900/50 border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td></template>
        <td class="text-left bg-gray-50 dark:bg-gray-900/50 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    {{-- Activity --}}
    @include('livewire.patient.adl-partials.multi-select-field', [
        'field' => 'activity',
        'formField' => 'activity',
        'label' => 'Activity',
        'enum' => \App\Enums\Activity::class,
        'borderClass' => 'border-b'
    ])

    {{-- Mobility --}}
    @include('livewire.patient.adl-partials.multi-select-field', [
        'field' => 'mobility',
        'formField' => 'mobility',
        'label' => 'Mobility',
        'enum' => \App\Enums\Mobility::class,
        'borderClass' => 'border-b'
    ])

    {{-- Level of Transfer Assist --}}
    @include('livewire.patient.adl-partials.multi-select-field', [
        'field' => 'level_of_transfer_assist',
        'formField' => 'levelOfTransferAssist',
        'label' => 'Level of Transfer Assist',
        'enum' => \App\Enums\LevelOfTransfer::class,
        'borderClass' => 'border-b'
    ])

    {{-- Assistive Device --}}
    @include('livewire.patient.adl-partials.multi-select-field', [
        'field' => 'assistive_device',
        'formField' => 'assistiveDevice',
        'label' => 'Assistive Device',
        'enum' => \App\Enums\MobilityAssistDevices::class,
        'borderClass' => 'border-b'
    ])

    {{-- Distance Ambulated --}}
    <tr class="border-b border-gray-200 dark:border-gray-700">
        <td class="text-left py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700" style="width: 256px; min-width: 256px; max-width: 256px;">Distance Ambulated (ft)</td>
        @foreach($this->adls as $adl)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-gray-900 dark:text-gray-100 text-xs text-center', 'line-through' => $adl->trashed()])>{{ $adl->distance_ambulated ?? '-' }}</span>
            </td>
        @endforeach
        <template x-if="showNewColumn">
            <td class="text-left py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:input type="number" wire:model="form.distanceAmbulated" placeholder="0" min="0" size="sm" />
            </td>
        </template>
        <td class="text-left bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    {{-- Ambulation Response --}}
    @include('livewire.patient.adl-partials.multi-select-field', [
        'field' => 'ambulation_response',
        'formField' => 'ambulationResponse',
        'label' => 'Ambulation Response',
        'enum' => \App\Enums\AmbulationResponse::class,
        'borderClass' => 'border-b'
    ])

    {{-- Level of Repositioning Assistance --}}
    @include('livewire.patient.adl-partials.multi-select-field', [
        'field' => 'level_of_repositioning_assistance',
        'formField' => 'levelOfRepositioningAssistance',
        'label' => 'Level of Repositioning Assistance',
        'enum' => \App\Enums\LevelOfRepositioning::class,
        'borderClass' => 'border-b'
    ])

    {{-- Repositioned --}}
    @include('livewire.patient.adl-partials.multi-select-field', [
        'field' => 'repositioned',
        'formField' => 'repositioned',
        'label' => 'Repositioned',
        'enum' => \App\Enums\Repositioned::class,
        'borderClass' => 'border-b'
    ])

    {{-- Positioning Frequency --}}
    @include('livewire.patient.adl-partials.multi-select-field', [
        'field' => 'positioning_frequency',
        'formField' => 'positioningFrequency',
        'label' => 'Positioning Frequency',
        'enum' => \App\Enums\PositioningFrequency::class,
        'borderClass' => 'border-b'
    ])

    {{-- Head of Bed Elevated --}}
    @include('livewire.patient.adl-partials.multi-select-field', [
        'field' => 'head_of_bed_elevated',
        'formField' => 'headOfBedElevated',
        'label' => 'Head of Bed Elevated',
        'enum' => \App\Enums\HeadBedElevated::class,
        'borderClass' => 'border-b'
    ])

    {{-- Heels/Feet --}}
    @include('livewire.patient.adl-partials.multi-select-field', [
        'field' => 'heels_feet',
        'formField' => 'heelsFeet',
        'label' => 'Heels/Feet',
        'enum' => \App\Enums\HandsFeet::class,
        'borderClass' => 'border-b'
    ])

    {{-- Hands/Arms --}}
    @include('livewire.patient.adl-partials.multi-select-field', [
        'field' => 'hands_arms',
        'formField' => 'handsArms',
        'label' => 'Hands/Arms',
        'enum' => \App\Enums\HandsArms::class,
        'borderClass' => 'border-b'
    ])

    {{-- Range of Motion --}}
    @include('livewire.patient.adl-partials.multi-select-field', [
        'field' => 'range_of_motion',
        'formField' => 'rangeOfMotion',
        'label' => 'Range of Motion',
        'enum' => \App\Enums\RangeOfMotion::class,
        'borderClass' => 'border-b'
    ])

    {{-- Transport Method --}}
    @include('livewire.patient.adl-partials.multi-select-field', [
        'field' => 'transport_method',
        'formField' => 'transportMethod',
        'label' => 'Transport Method',
        'enum' => \App\Enums\TransportMethod::class,
        'borderClass' => 'border-b'
    ])

    {{-- Anti Embolism Device --}}
    @include('livewire.patient.adl-partials.multi-select-field', [
        'field' => 'anti_embolism_device',
        'formField' => 'antiEmbolismDevice',
        'label' => 'Anti Embolism Device',
        'enum' => \App\Enums\AntiEmbolismDevice::class,
        'borderClass' => 'border-b'
    ])

    {{-- Anti Embolism Status --}}
    @include('livewire.patient.adl-partials.multi-select-field', [
        'field' => 'anti_embolism_status',
        'formField' => 'antiEmbolismStatus',
        'label' => 'Anti Embolism Status',
        'enum' => \App\Enums\AntiEmbolismStatus::class,
        'borderClass' => 'border-b'
    ])
    </tbody>
</table>
</div>

<div x-show="showNewColumn" class="fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-900 border-t p-4 flex justify-end shadow-lg z-50">
    <flux:button variant="primary" wire:click="save">
        <span wire:loading.remove>Save ADL Record</span>
        <span wire:loading>Saving...</span>
    </flux:button>
</div>
</div>
