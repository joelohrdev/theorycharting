<div x-data="{
    showNewColumn: false,
    addColumn() {
        this.showNewColumn = true;
    },
    cancelNewColumn() {
        this.showNewColumn = false;
    },
    formatTime(datetime) {
        if (!datetime) return '';
        const date = new Date(datetime);
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        return hours + minutes;
    }
}" wire:key="vitals-form-{{ $patient->id }}">
<table class="w-full border-collapse text-sm">
    <colgroup>
        <col style="width: 256px; min-width: 256px; max-width: 256px;">
        @foreach($this->vitals as $vital)
            <col>
        @endforeach
        <col x-show="showNewColumn">
        <col style="width: 160px; min-width: 160px; max-width: 160px;">
    </colgroup>
    <thead>
    <tr class="border-b border-gray-200 dark:border-gray-700">
        <th class="text-left py-3 px-4 bg-gray-50 dark:bg-gray-900 sticky left-0 z-20 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)]" style="width: 256px; min-width: 256px; max-width: 256px;">
            <span class="font-semibold text-gray-900 dark:text-gray-100">Vital Signs</span>
        </th>
        <!-- Existing saved vitals -->
        @foreach($this->vitals as $vital)
            <th class="group relative text-center py-3 px-2 bg-gray-50 dark:bg-gray-900 border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:tooltip>
                    <span @class(['font-semibold text-gray-900 dark:text-gray-100', 'line-through' => $vital->trashed()])>
                        {{ $vital->created_at->format('H:i') }}
                    </span>
                    <x-slot:content>
                        <p>{{ $vital->created_at->format('F d, Y H:i') }}<br>{{ $vital->user->name }}</p>
                        @if($vital->trashed())
                        <p class="mt-3">Deleted:<br> {{ $vital->deleted_at->format('F d, Y H:i') }}<br>{{ $vital->deletedBy->name }}</p>
                        @endif
                    </x-slot:content>
                </flux:tooltip>
                @if(!$vital->trashed())
                <div class="absolute right-2 inset-y-0 flex items-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <livewire:forms.delete :$vital />
                </div>
                @endif
                @if($vital->trashed())
                    <div class="absolute right-2 inset-y-0 flex items-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <livewire:forms.restore :$vital />
                    </div>
                @endif
            </th>
        @endforeach
        <!-- New column input -->
        <template x-if="showNewColumn">
            <th class="text-center py-3 px-2 bg-gray-50 dark:bg-gray-900 border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span class="font-semibold text-gray-900 dark:text-gray-100">New</span>
            </th>
        </template>
        <th class="py-3 px-4 bg-gray-50 dark:bg-gray-900 sticky right-0 z-20" style="width: 160px; min-width: 160px; max-width: 160px;">
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
    <tr class="border-b border-gray-100 dark:border-gray-800">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Temperature</td>
        <!-- Existing vitals (read-only) -->
        @foreach($this->vitals as $vital)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-xs text-gray-900 dark:text-gray-100', 'line-through' => $vital->trashed()])>{{ $vital->temperature ? number_format($vital->temperature / 10, 1) : '-' }}</span>
            </td>
        @endforeach
        <!-- New column (editable) -->
        <template x-if="showNewColumn">
            <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700 text-left" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:input type="number"
                            wire:model="form.temperature"
                            placeholder="98.6"
                            step="0.1"
                            min="50"
                            max="115"
                            size="sm" />
            </td>
        </template>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b border-gray-100 dark:border-gray-800">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Temperature Source</td>
        @foreach($this->vitals as $vital)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-xs text-gray-900 dark:text-gray-100', 'line-through' => $vital->trashed()])>{{ $vital->temperature_source?->label() ?? '-' }}</span>
            </td>
        @endforeach
        <template x-if="showNewColumn" >
            <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700 text-left" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:select wire:model="form.temperatureSource" placeholder="Select..." autocomplete="off" size="sm">
                    @foreach(\App\Enums\Temp::cases() as $temp)
                        <flux:select.option value="{{ $temp->value }}">{{ $temp->label() }}</flux:select.option>
                    @endforeach
                </flux:select>
            </td>
        </template>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b border-gray-100 dark:border-gray-800">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Heart Rate</td>
        @foreach($this->vitals as $vital)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-xs text-gray-900 dark:text-gray-100', 'line-through' => $vital->trashed()])>{{ $vital->heart_rate ?? '-' }}</span>
            </td>
        @endforeach
        <template x-if="showNewColumn" >
            <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700 text-left" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:input type="number"
                            wire:model="form.heartRate"
                            placeholder="75"
                            min="1"
                            max="300"
                            size="sm" />
            </td>
        </template>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b border-gray-100 dark:border-gray-800">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Heart Rate Source</td>
        @foreach($this->vitals as $vital)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-xs text-gray-900 dark:text-gray-100', 'line-through' => $vital->trashed()])>{{ $vital->heart_rate_source?->label() ?? '-' }}</span>
            </td>
        @endforeach
        <template x-if="showNewColumn" >
            <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700 text-left" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:select wire:model="form.heartRateSource" placeholder="Select..." autocomplete="off" size="sm">
                    @foreach(\App\Enums\Heart::cases() as $heart)
                        <flux:select.option value="{{ $heart->value }}">{{ $heart->label() }}</flux:select.option>
                    @endforeach
                </flux:select>
            </td>
        </template>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b border-gray-100 dark:border-gray-800">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Resp</td>
        @foreach($this->vitals as $vital)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-xs text-gray-900 dark:text-gray-100', 'line-through' => $vital->trashed()])>{{ $vital->resp ?? '-' }}</span>
            </td>
        @endforeach
        <template x-if="showNewColumn" >
            <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700 text-left" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:input type="number"
                            wire:model="form.resp"
                            placeholder="16"
                            min="1"
                            max="100"
                            size="sm" />
            </td>
        </template>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b border-gray-100 dark:border-gray-800">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">BP</td>
        @foreach($this->vitals as $vital)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-xs text-gray-900 dark:text-gray-100', 'line-through' => $vital->trashed()])>{{ $vital->systolic && $vital->diastolic ? $vital->systolic . "/" . $vital->diastolic : "-" ?? '-' }}</span>
            </td>
        @endforeach
        <template x-if="showNewColumn" >
            <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700 text-left" style="width: 160px; min-width: 160px; max-width: 160px;">
                <div class="flex items-center gap-2">
                    <flux:input
                        type="number"
                        wire:model="form.systolic"
                        placeholder="85"
                        class="w-20"
                        min="1"
                        max="300"
                        size="sm"
                    />
                    <span class="text-gray-500">/</span>
                    <flux:input
                        type="number"
                        wire:model="form.diastolic"
                        placeholder="85"
                        class="w-20"
                        min="1"
                        max="200"
                        size="sm"
                    />
                </div>
            </td>
        </template>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b border-gray-100 dark:border-gray-800">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">BP Source</td>
        @foreach($this->vitals as $vital)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-xs text-gray-900 dark:text-gray-100', 'line-through' => $vital->trashed()])>{{ $vital->bp_source?->label() ?? '-' }}</span>
            </td>
        @endforeach
        <template x-if="showNewColumn" >
            <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700 text-left" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:select wire:model="form.bpSource" placeholder="Select..." autocomplete="off" size="sm">
                    @foreach(\App\Enums\BpSource::cases() as $source)
                        <flux:select.option value="{{ $source->value }}">{{ $source->label() }}</flux:select.option>
                    @endforeach
                </flux:select>
            </td>
        </template>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b border-gray-100 dark:border-gray-800">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">BP Method</td>
        @foreach($this->vitals as $vital)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-xs text-gray-900 dark:text-gray-100', 'line-through' => $vital->trashed()])>{{ $vital->bp_method?->label() ?? '-' }}</span>
            </td>
        @endforeach
        <template x-if="showNewColumn" >
            <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700 text-left" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:select wire:model="form.bpMethod" placeholder="Select..." autocomplete="off" size="sm">
                    @foreach(\App\Enums\BpMethod::cases() as $method)
                        <flux:select.option value="{{ $method->value }}">{{ $method->label() }}</flux:select.option>
                    @endforeach
                </flux:select>
            </td>
        </template>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b border-gray-100 dark:border-gray-800">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Patient Position</td>
        @foreach($this->vitals as $vital)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-xs text-gray-900 dark:text-gray-100', 'line-through' => $vital->trashed()])>{{ $vital->patient_position?->label() ?? '-' }}</span>
            </td>
        @endforeach
        <template x-if="showNewColumn" >
            <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700 text-left" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:select wire:model="form.patientPosition" placeholder="Select..." autocomplete="off" size="sm">
                    @foreach(\App\Enums\PatientPosition::cases() as $position)
                        <flux:select.option value="{{ $position->value }}">{{ $position->label() }}</flux:select.option>
                    @endforeach
                </flux:select>
            </td>
        </template>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b-4 border-gray-200 dark:border-gray-700">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Abdominal Girth</td>
        @foreach($this->vitals as $vital)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-xs text-gray-900 dark:text-gray-100', 'line-through' => $vital->trashed()])>{{ $vital->abdominal_girth ?? '-' }}</span>
            </td>
        @endforeach
        <template x-if="showNewColumn" >
            <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700 text-left" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:input
                    type="number"
                    wire:model="form.abdominalGirth"
                    placeholder="cm"
                    step="0.1"
                    size="sm"
                />
            </td>
        </template>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="bg-gray-50 dark:bg-gray-900/50">
        <td class="py-3 px-4 font-semibold text-gray-900 dark:text-gray-100 sticky left-0 z-10 bg-gray-50 dark:bg-gray-900/50 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Pain Assessment</td>
        @foreach($this->vitals as $vital)
            <td class="bg-gray-50 dark:bg-gray-900/50 border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
        @endforeach
        <template x-if="showNewColumn" >
            <td class="bg-gray-50 dark:bg-gray-900/50 border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
        </template>
        <td class="bg-gray-50 dark:bg-gray-900/50 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b border-gray-100 dark:border-gray-800">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Pain Scale</td>
        @foreach($this->vitals as $vital)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-xs text-gray-900 dark:text-gray-100', 'line-through' => $vital->trashed()])>{{ $vital->pain_scale?->label() ?? '-' }}</span>
            </td>
        @endforeach
        <template x-if="showNewColumn" >
            <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700 text-left" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:select wire:model.live="form.painScale" placeholder="Select..." autocomplete="off" size="sm">
                    @foreach(\App\Enums\PainScale::cases() as $scale)
                        <flux:select.option value="{{ $scale->value }}">{{ $scale->label() }}</flux:select.option>
                    @endforeach
                </flux:select>
            </td>
        </template>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b border-gray-100 dark:border-gray-800">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Pain Score</td>
        @foreach($this->vitals as $vital)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-xs text-gray-900 dark:text-gray-100', 'line-through' => $vital->trashed()])>
                    @if($vital->pain_scale === \App\Enums\PainScale::FACE && $vital->pain_score)
                        {{ \App\Enums\FacePainScale::from($vital->pain_score)->label() }}
                    @else
                        {{ $vital->pain_score ?? '-' }}
                    @endif
                </span>
            </td>
        @endforeach
        <template x-if="showNewColumn" >
            <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700 text-left" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:select wire:model.live="form.painScore" placeholder="Select..." autocomplete="off" size="sm">
                    @if($this->form->painScale === \App\Enums\PainScale::NUMERIC->value)
                        @foreach(\App\Enums\NumericPainScale::cases() as $scale)
                            <flux:select.option value="{{ $scale->value }}">{{ $scale->label() }}</flux:select.option>
                        @endforeach
                    @elseif($this->form->painScale === \App\Enums\PainScale::FACE->value)
                        @foreach(\App\Enums\FacePainScale::cases() as $scale)
                            <flux:select.option value="{{ $scale->value }}">{{ $scale->label() }}</flux:select.option>
                        @endforeach
                    @endif
                </flux:select>
            </td>
        </template>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b border-gray-100 dark:border-gray-800">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Pain Goal</td>
        @foreach($this->vitals as $vital)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-xs text-gray-900 dark:text-gray-100', 'line-through' => $vital->trashed()])>{{ $vital->pain_goal ?? '-' }}</span>
            </td>
        @endforeach
        <template x-if="showNewColumn" >
            <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700 text-left" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:input
                    type="number"
                    wire:model="form.painGoal"
                    placeholder="0-10"
                    min="0"
                    max="10"
                    size="sm"
                />
            </td>
        </template>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b border-gray-100 dark:border-gray-800">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Pain Location</td>
        @foreach($this->vitals as $vital)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-xs text-gray-900 dark:text-gray-100', 'line-through' => $vital->trashed()])>{{ $vital->pain_location ?? '-' }}</span>
            </td>
        @endforeach
        <template x-if="showNewColumn" >
            <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700 text-left" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:input
                    type="text"
                    wire:model="form.painLocation"
                    placeholder="e.g., Abdomen"
                    size="sm"
                />
            </td>
        </template>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b-4 border-gray-200 dark:border-gray-700">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Pain Descriptors</td>
        @foreach($this->vitals as $vital)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-xs text-gray-900 dark:text-gray-100', 'line-through' => $vital->trashed()])>{{ $vital->pain_descriptors ? implode(", ", array_map(fn($d) => \App\Enums\PainDescriptors::from($d)->label(), $vital->pain_descriptors)) : '-' }}</span>
            </td>
        @endforeach
        <template x-if="showNewColumn" >
            <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700 text-left" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:select wire:model="form.painDescriptors" variant="listbox" multiple placeholder="Select..." autocomplete="off" size="sm">
                    @foreach(\App\Enums\PainDescriptors::cases() as $descriptor)
                        <flux:select.option value="{{ $descriptor->value }}">{{ $descriptor->label() }}</flux:select.option>
                    @endforeach
                </flux:select>
            </td>
        </template>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="bg-gray-50 dark:bg-gray-900/50">
        <td class="py-3 px-4 font-semibold text-gray-900 dark:text-gray-100 sticky left-0 z-10 bg-gray-50 dark:bg-gray-900/50 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Oxygen Therapy</td>
        @foreach($this->vitals as $vital)
            <td class="bg-gray-50 dark:bg-gray-900/50 border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
        @endforeach
        <template x-if="showNewColumn" >
            <td class="bg-gray-50 dark:bg-gray-900/50 border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
        </template>
        <td class="bg-gray-50 dark:bg-gray-900/50 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b border-gray-100 dark:border-gray-800">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">SpO2</td>
        @foreach($this->vitals as $vital)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-xs text-gray-900 dark:text-gray-100', 'line-through' => $vital->trashed()])>{{ $vital->sp02 ?? '-' }}</span>
            </td>
        @endforeach
        <template x-if="showNewColumn" >
            <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700 text-left" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:input
                    type="number"
                    wire:model="form.sp02"
                    placeholder="93"
                    min="0"
                    max="100"
                    size="sm"
                />
            </td>
        </template>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b border-gray-100 dark:border-gray-800">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Oxygen Device</td>
        @foreach($this->vitals as $vital)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-xs text-gray-900 dark:text-gray-100', 'line-through' => $vital->trashed()])>{{ $vital->oxygen_device?->label() ?? '-' }}</span>
            </td>
        @endforeach
        <template x-if="showNewColumn" >
            <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700 text-left" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:select wire:model="form.oxygenDevice" placeholder="Select..." autocomplete="off" size="sm">
                    @foreach(\App\Enums\Oxygen::cases() as $oxygen)
                        <flux:select.option value="{{ $oxygen->value }}">{{ $oxygen->label() }}</flux:select.option>
                    @endforeach
                </flux:select>
            </td>
        </template>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>
    </tbody>
</table>

<div class="mt-6 flex justify-end" x-show="showNewColumn">
    <flux:button variant="primary" wire:click="save">
        <span wire:loading.remove>Save Vital Signs</span>
        <span wire:loading>Saving...</span>
    </flux:button>
</div>
</div>
