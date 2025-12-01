@php
$hasOther = collect($this->adls)->filter(fn($adl) => !empty($adl->$field) && in_array('other', $adl->$field))->count() > 0 || (isset($this->form) && in_array('other', $this->form->$formField ?? []));
$commentField = $field . '_comment';
$formCommentField = $formField . 'Comment';
@endphp

{{-- Main Field Row --}}
<tr class="border-b border-gray-100 dark:border-gray-800">
    <td class="text-left py-2 px-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700" style="width: 256px; min-width: 256px; max-width: 256px;">{{ $label }}</td>
    @foreach($this->adls as $adl)
        <td class="py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
            <span @class(['text-gray-900 dark:text-gray-100 text-xs', 'line-through' => $adl->trashed()])>
                {{ !empty($adl->$field) ? implode(", ", array_map(fn($v) => $enum::from($v)->label(), $adl->$field)) : '-' }}
            </span>
        </td>
    @endforeach
    <template x-if="showNewColumn">
        <td class="text-left py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
            <flux:select wire:model.live="form.{{ $formField }}" variant="listbox" multiple placeholder="Select..." autocomplete="off" size="sm">
                @foreach($enum::cases() as $option)
                    <flux:select.option value="{{ $option->value }}">{{ $option->label() }}</flux:select.option>
                @endforeach
            </flux:select>
        </td>
    </template>
    <td class="text-left bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
</tr>

{{-- Comment Row (only if "Other" is selected) --}}
@if($hasOther)
<tr class="{{ $borderClass }} border-gray-200 dark:border-gray-700">
    <td class="text-left py-2 px-4 pl-8 font-medium text-gray-600 dark:text-gray-400 text-sm bg-white dark:bg-gray-950 sticky left-0 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] border-r border-gray-200 dark:border-gray-700" style="width: 256px; min-width: 256px; max-width: 256px;">Comment</td>
    @foreach($this->adls as $adl)
        <td class="text-left py-2 px-2 bg-white dark:bg-gray-950 text-center border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
            @if(!empty($adl->$field) && in_array('other', $adl->$field))
                <span @class(['text-gray-900 dark:text-gray-100 text-xs', 'line-through' => $adl->trashed()])>{{ $adl->$commentField ?? '-' }}</span>
            @endif
        </td>
    @endforeach
    <template x-if="showNewColumn">
        <td class="text-left py-2 px-2 bg-white dark:bg-gray-950 border-l border-r border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;">
            @if(in_array('other', $this->form->$formField ?? []))
                <flux:textarea wire:model="form.{{ $formCommentField }}" placeholder="Comment..." rows="2" size="sm" />
            @endif
        </td>
    </template>
    <td class="text-left bg-white dark:bg-gray-950 sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700" style="width: 160px; min-width: 160px; max-width: 160px;"></td>
</tr>
@else
<tr class="{{ $borderClass }} border-gray-200 dark:border-gray-700">
    <td colspan="100" class="p-0"></td>
</tr>
@endif
