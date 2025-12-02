<div x-data="{
    showNewColumn: false,
    addColumn() {
        this.showNewColumn = true;
        setTimeout(() => {
            const wrapper = document.getElementById('intake-table-wrapper');
            if (wrapper) wrapper.scrollTo({ left: 999999, behavior: 'smooth' });
        }, 200);
    },
    cancelNewColumn() {
        this.showNewColumn = false;
    }
}" wire:key="intake-form-{{ $patient->id }}">
<div id="intake-table-wrapper" style="overflow-x: auto; margin-bottom: 100px;">
<table class="w-full border-collapse text-sm">
    <colgroup>
        <col style="width: 256px; min-width: 256px; max-width: 256px;">
        @foreach($this->intakes as $intake)
            <col>
        @endforeach
        <col x-show="showNewColumn">
        <col style="width: 160px; min-width: 160px; max-width: 160px;">
    </colgroup>
    <thead>
    <tr class="border-b border-gray-200 dark:border-gray-700">
        <th class="text-left py-3 px-4 bg-gray-50 dark:bg-gray-900 sticky left-0 z-20 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)]" style="width: 256px; min-width: 256px; max-width: 256px;">
            <span class="font-semibold text-gray-900 dark:text-gray-100">Intake/Output</span>
        </th>
        @foreach($this->intakes as $intake)
            <th class="group relative text-center py-3 px-2 bg-gray-50 dark:bg-gray-900 border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:tooltip>
                    <span @class(['font-semibold text-gray-900 dark:text-gray-100', 'line-through' => $intake->trashed()])>
                        {{ $intake->created_at->format('H:i') }}
                    </span>
                    <x-slot:content>
                        <p>{{ $intake->created_at->format('F d, Y H:i') }}<br>{{ $intake->user->name }}</p>
                        @if($intake->trashed())
                        <p class="mt-3">Deleted:<br> {{ $intake->deleted_at->format('F d, Y H:i') }}<br>{{ $intake->deletedBy->name }}</p>
                        @endif
                    </x-slot:content>
                </flux:tooltip>
                @if(!$intake->trashed())
                <div class="absolute right-2 inset-y-0 flex items-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <livewire:forms.delete :$intake />
                </div>
                @endif
                @if($intake->trashed())
                    <div class="absolute right-2 inset-y-0 flex items-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <livewire:forms.restore :$intake />
                    </div>
                @endif
            </th>
        @endforeach
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
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Appetite</td>
        @foreach($this->intakes as $intake)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-xs text-gray-900 dark:text-gray-100', 'line-through' => $intake->trashed()])>
                    {{ $intake->appetite ? implode(", ", array_map(fn($a) => \App\Enums\Appetite::from($a)->label(), $intake->appetite)) : '-' }}
                </span>
            </td>
        @endforeach
        <template x-if="showNewColumn">
            <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700 text-left" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:select wire:model.live="form.appetite" variant="listbox" multiple placeholder="Select..." autocomplete="off" size="sm">
                    @foreach(\App\Enums\Appetite::cases() as $app)
                        <flux:select.option value="{{ $app->value }}">{{ $app->label() }}</flux:select.option>
                    @endforeach
                </flux:select>
            </td>
        </template>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b border-gray-100 dark:border-gray-800" x-show="showNewColumn && $wire.form.appetite.includes('other')">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Appetite Comment</td>
        @foreach($this->intakes as $intake)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-left border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
        @endforeach
        <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700" style="width: 128px; min-width: 128px; max-width: 128px;">
            <flux:textarea wire:model="form.appetiteComment" rows="2" placeholder="Enter comment..." size="sm"></flux:textarea>
        </td>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b border-gray-100 dark:border-gray-800">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Unable to Eat/Drink</td>
        @foreach($this->intakes as $intake)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-xs text-gray-900 dark:text-gray-100', 'line-through' => $intake->trashed()])>
                    {{ $intake->unable_to_eat_drink ? implode(", ", array_map(fn($u) => \App\Enums\UnableToEatDrink::from($u)->label(), $intake->unable_to_eat_drink)) : '-' }}
                </span>
            </td>
        @endforeach
        <template x-if="showNewColumn">
            <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700 text-left" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:select wire:model.live="form.unableToEatDrink" variant="listbox" multiple placeholder="Select..." autocomplete="off" size="sm">
                    @foreach(\App\Enums\UnableToEatDrink::cases() as $app)
                        <flux:select.option value="{{ $app->value }}">{{ $app->label() }}</flux:select.option>
                    @endforeach
                </flux:select>
            </td>
        </template>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b border-gray-100 dark:border-gray-800" x-show="showNewColumn && $wire.form.unableToEatDrink.includes('other')">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Unable to Eat/Drink Comment</td>
        @foreach($this->intakes as $intake)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-left border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
        @endforeach
        <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700" style="width: 128px; min-width: 128px; max-width: 128px;">
            <flux:textarea wire:model="form.unableToEatDrinkComment" rows="2" placeholder="Enter comment..." size="sm"></flux:textarea>
        </td>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b border-gray-100 dark:border-gray-800">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Percentage Eaten</td>
        @foreach($this->intakes as $intake)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-xs text-gray-900 dark:text-gray-100', 'line-through' => $intake->trashed()])>{{ $intake->percentage_eaten ?? '-' }}</span>
            </td>
        @endforeach
        <template x-if="showNewColumn">
            <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700 text-left" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:input type="number" wire:model="form.percentageEaten" min="0" max="100" step="1" placeholder="0" autocomplete="off" size="sm"></flux:input>
            </td>
        </template>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b-4 border-gray-200 dark:border-gray-700">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Liquids (mL)</td>
        @foreach($this->intakes as $intake)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-xs text-gray-900 dark:text-gray-100', 'line-through' => $intake->trashed()])>{{ $intake->liquids ?? '-' }}</span>
            </td>
        @endforeach
        <template x-if="showNewColumn">
            <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700 text-left" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:input type="number" wire:model="form.liquids" min="0" step="1" placeholder="0" autocomplete="off" size="sm"></flux:input>
            </td>
        </template>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="bg-gray-50 dark:bg-gray-900/50">
        <td class="py-3 px-4 font-semibold text-gray-900 text-left dark:text-gray-100 sticky left-0 z-10 bg-gray-50 dark:bg-gray-900/50 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700" style="width: 256px; min-width: 256px; max-width: 256px;">Urine</td>
        @foreach($this->intakes as $intake)
            <td class="bg-gray-50 dark:bg-gray-900/50 border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
        @endforeach
        <template x-if="showNewColumn">
            <td class="bg-gray-50 dark:bg-gray-900/50 border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
        </template>
        <td class="bg-gray-50 dark:bg-gray-900/50 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b border-gray-100 dark:border-gray-800">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Urine (mL)</td>
        @foreach($this->intakes as $intake)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-xs text-gray-900 dark:text-gray-100', 'line-through' => $intake->trashed()])>{{ $intake->urine ?? '-' }}</span>
            </td>
        @endforeach
        <template x-if="showNewColumn">
            <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700 text-left" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:input type="number" wire:model="form.urine" min="0" step="1" placeholder="0" autocomplete="off" size="sm"></flux:input>
            </td>
        </template>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b border-gray-100 dark:border-gray-800">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Unmeasured Urine Occurrence</td>
        @foreach($this->intakes as $intake)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-xs text-gray-900 dark:text-gray-100', 'line-through' => $intake->trashed()])>{{ $intake->unmeasured_urine_occurrence ?? '-' }}</span>
            </td>
        @endforeach
        <template x-if="showNewColumn">
            <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700 text-left" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:input type="number" wire:model="form.unmeasuredUrineOccurrence" min="0" step="1" placeholder="0" autocomplete="off" size="sm"></flux:input>
            </td>
        </template>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr :class="showNewColumn && $wire.form.urineCharacteristics.includes('other') ? 'border-b border-gray-100 dark:border-gray-800' : 'border-b-4 border-gray-200 dark:border-gray-700'">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Urine Characteristics</td>
        @foreach($this->intakes as $intake)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-xs text-gray-900 dark:text-gray-100', 'line-through' => $intake->trashed()])>
                    {{ $intake->urine_characteristics ? implode(", ", array_map(fn($c) => \App\Enums\UrineCharacteristics::from($c)->label(), $intake->urine_characteristics)) : '-' }}
                </span>
            </td>
        @endforeach
        <template x-if="showNewColumn">
            <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700 text-left" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:select wire:model.live="form.urineCharacteristics" variant="listbox" multiple placeholder="Select..." autocomplete="off" size="sm">
                    @foreach(\App\Enums\UrineCharacteristics::cases() as $uc)
                        <flux:select.option value="{{ $uc->value }}">{{ $uc->label() }}</flux:select.option>
                    @endforeach
                </flux:select>
            </td>
        </template>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b-4 border-gray-200 dark:border-gray-700" x-show="showNewColumn && $wire.form.urineCharacteristics.includes('other')">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Urine Characteristics Comment</td>
        @foreach($this->intakes as $intake)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-left border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
        @endforeach
        <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700" style="width: 128px; min-width: 128px; max-width: 128px;">
            <flux:textarea wire:model="form.urineCharacteristicsComment" rows="2" placeholder="Enter comment..." size="sm"></flux:textarea>
        </td>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="bg-gray-50 dark:bg-gray-900/50">
        <td class="py-3 px-4 font-semibold text-gray-900 dark:text-gray-100 text-left sticky left-0 z-10 bg-gray-50 dark:bg-gray-900/50 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700" style="width: 192px; min-width: 192px; max-width: 192px;">Stool</td>
        @foreach($this->intakes as $intake)
            <td class="bg-gray-50 dark:bg-gray-900/50 border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
        @endforeach
        <template x-if="showNewColumn">
            <td class="bg-gray-50 dark:bg-gray-900/50 border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
        </template>
        <td class="bg-gray-50 dark:bg-gray-900/50 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b border-gray-100 dark:border-gray-800">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Stool (mL)</td>
        @foreach($this->intakes as $intake)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-xs text-gray-900 dark:text-gray-100', 'line-through' => $intake->trashed()])>{{ $intake->stool ?? '-' }}</span>
            </td>
        @endforeach
        <template x-if="showNewColumn">
            <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700 text-left" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:input type="number" wire:model="form.stool" min="0" step="1" placeholder="0" autocomplete="off" size="sm"></flux:input>
            </td>
        </template>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b border-gray-100 dark:border-gray-800">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Stool Amount</td>
        @foreach($this->intakes as $intake)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-xs text-gray-900 dark:text-gray-100', 'line-through' => $intake->trashed()])>
                    {{ $intake->stool_amount ? implode(", ", array_map(fn($a) => \App\Enums\StoolAmount::from($a)->label(), $intake->stool_amount)) : '-' }}
                </span>
            </td>
        @endforeach
        <template x-if="showNewColumn">
            <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700 text-left" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:select wire:model.live="form.stoolAmount" variant="listbox" multiple placeholder="Select..." autocomplete="off" size="sm">
                    @foreach(\App\Enums\StoolAmount::cases() as $sa)
                        <flux:select.option value="{{ $sa->value }}">{{ $sa->label() }}</flux:select.option>
                    @endforeach
                </flux:select>
            </td>
        </template>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b border-gray-100 dark:border-gray-800" x-show="showNewColumn && $wire.form.stoolAmount.includes('other')">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Stool Amount Comment</td>
        @foreach($this->intakes as $intake)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-left border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
        @endforeach
        <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700" style="width: 128px; min-width: 128px; max-width: 128px;">
            <flux:textarea wire:model="form.stoolAmountComment" rows="2" placeholder="Enter comment..." size="sm"></flux:textarea>
        </td>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b border-gray-100 dark:border-gray-800">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Unmeasured Stool Occurrence</td>
        @foreach($this->intakes as $intake)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-xs text-gray-900 dark:text-gray-100', 'line-through' => $intake->trashed()])>{{ $intake->unmeasured_stool_occurrence ?? '-' }}</span>
            </td>
        @endforeach
        <template x-if="showNewColumn">
            <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700 text-left" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:input type="number" wire:model="form.unmeasuredStoolOccurrence" min="0" step="1" placeholder="0" autocomplete="off" size="sm"></flux:input>
            </td>
        </template>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr :class="showNewColumn && $wire.form.stoolCharacteristics.includes('other') ? 'border-b border-gray-100 dark:border-gray-800' : 'border-b-4 border-gray-200 dark:border-gray-700'">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Stool Characteristics</td>
        @foreach($this->intakes as $intake)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-xs text-gray-900 dark:text-gray-100', 'line-through' => $intake->trashed()])>
                    {{ $intake->stool_characteristics ? implode(", ", array_map(fn($c) => \App\Enums\StoolCharacteristics::from($c)->label(), $intake->stool_characteristics)) : '-' }}
                </span>
            </td>
        @endforeach
        <template x-if="showNewColumn">
            <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700 text-left" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:select wire:model.live="form.stoolCharacteristics" variant="listbox" multiple placeholder="Select..." autocomplete="off" size="sm">
                    @foreach(\App\Enums\StoolCharacteristics::cases() as $sc)
                        <flux:select.option value="{{ $sc->value }}">{{ $sc->label() }}</flux:select.option>
                    @endforeach
                </flux:select>
            </td>
        </template>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b-4 border-gray-200 dark:border-gray-700" x-show="showNewColumn && $wire.form.stoolCharacteristics.includes('other')">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Stool Characteristics Comment</td>
        @foreach($this->intakes as $intake)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-left border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
        @endforeach
        <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700" style="width: 128px; min-width: 128px; max-width: 128px;">
            <flux:textarea wire:model="form.stoolCharacteristicsComment" rows="2" placeholder="Enter comment..." size="sm"></flux:textarea>
        </td>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="bg-gray-50 dark:bg-gray-900/50">
        <td class="py-3 px-4 font-semibold text-gray-900 dark:text-gray-100 text-left sticky left-0 z-10 bg-gray-50 dark:bg-gray-900/50 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700" style="width: 192px; min-width: 192px; max-width: 192px;">Emesis</td>
        @foreach($this->intakes as $intake)
            <td class="bg-gray-50 dark:bg-gray-900/50 border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
        @endforeach
        <template x-if="showNewColumn">
            <td class="bg-gray-50 dark:bg-gray-900/50 border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
        </template>
        <td class="bg-gray-50 dark:bg-gray-900/50 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b border-gray-100 dark:border-gray-800">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Emesis Amount</td>
        @foreach($this->intakes as $intake)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-xs text-gray-900 dark:text-gray-100', 'line-through' => $intake->trashed()])>
                    {{ $intake->emesis_amount ? implode(", ", array_map(fn($a) => \App\Enums\EmesisAmount::from($a)->label(), $intake->emesis_amount)) : '-' }}
                </span>
            </td>
        @endforeach
        <template x-if="showNewColumn">
            <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700 text-left" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:select wire:model.live="form.emesisAmount" variant="listbox" multiple placeholder="Select..." autocomplete="off" size="sm">
                    @foreach(\App\Enums\EmesisAmount::cases() as $ea)
                        <flux:select.option value="{{ $ea->value }}">{{ $ea->label() }}</flux:select.option>
                    @endforeach
                </flux:select>
            </td>
        </template>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b border-gray-100 dark:border-gray-800" x-show="showNewColumn && $wire.form.emesisAmount.includes('other')">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Emesis Amount Comment</td>
        @foreach($this->intakes as $intake)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-left border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
        @endforeach
        <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700" style="width: 128px; min-width: 128px; max-width: 128px;">
            <flux:textarea wire:model="form.emesisAmountComment" rows="2" placeholder="Enter comment..." size="sm"></flux:textarea>
        </td>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b border-gray-100 dark:border-gray-800">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Unmeasured Emesis Occurrence</td>
        @foreach($this->intakes as $intake)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-xs text-gray-900 dark:text-gray-100', 'line-through' => $intake->trashed()])>{{ $intake->unmeasured_emesis_occurrence ?? '-' }}</span>
            </td>
        @endforeach
        <template x-if="showNewColumn">
            <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700 text-left" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:input type="number" wire:model="form.unmeasuredEmesisOccurrence" min="0" step="1" placeholder="0" autocomplete="off" size="sm"></flux:input>
            </td>
        </template>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b border-gray-100 dark:border-gray-800">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Emesis Characteristics</td>
        @foreach($this->intakes as $intake)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
                <span @class(['text-xs text-gray-900 dark:text-gray-100', 'line-through' => $intake->trashed()])>
                    {{ $intake->emesis_characteristics ? implode(", ", array_map(fn($c) => \App\Enums\EmesisCharacteristics::from($c)->label(), $intake->emesis_characteristics)) : '-' }}
                </span>
            </td>
        @endforeach
        <template x-if="showNewColumn">
            <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700 text-left" style="width: 160px; min-width: 160px; max-width: 160px;">
                <flux:select wire:model.live="form.emesisCharacteristics" variant="listbox" multiple placeholder="Select..." autocomplete="off" size="sm">
                    @foreach(\App\Enums\EmesisCharacteristics::cases() as $ec)
                        <flux:select.option value="{{ $ec->value }}">{{ $ec->label() }}</flux:select.option>
                    @endforeach
                </flux:select>
            </td>
        </template>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>

    <tr class="border-b border-gray-100 dark:border-gray-800" x-show="showNewColumn && $wire.form.emesisCharacteristics.includes('other')">
        <td class="py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700 text-left" style="width: 256px; min-width: 256px; max-width: 256px;">Emesis Characteristics Comment</td>
        @foreach($this->intakes as $intake)
            <td class="py-2 px-2 bg-white dark:bg-gray-950 text-left border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
        @endforeach
        <td class="py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700" style="width: 128px; min-width: 128px; max-width: 128px;">
            <flux:textarea wire:model="form.emesisCharacteristicsComment" rows="2" placeholder="Enter comment..." size="sm"></flux:textarea>
        </td>
        <td class="bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
    </tr>
    </tbody>
</table>
</div>

<div x-show="showNewColumn" class="fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-900 border-t p-4 flex justify-end shadow-lg z-50">
    <flux:button variant="primary" wire:click="save">
        <span wire:loading.remove>Save Intake/Output</span>
        <span wire:loading>Saving...</span>
    </flux:button>
</div>
</div>
