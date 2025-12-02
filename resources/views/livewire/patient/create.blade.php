<div class="max-w-xl mx-auto">
    <form wire:submit="create" class="space-y-6">
        <flux:field>
            <flux:label>Name</flux:label>
            <flux:input wire:model="form.name" />
            <flux:error name="form.name" />
        </flux:field>

        <flux:field>
            <flux:label>Gender</flux:label>
            <flux:select wire:model="form.gender" placeholder="Choose...">
                <flux:select.option>Male</flux:select.option>
                <flux:select.option>Female</flux:select.option>
            </flux:select>
            <flux:error name="form.gender" />
        </flux:field>

        <flux:field>
            <flux:label>Birthdate</flux:label>
            <flux:date-picker max="today" wire:model="form.birth_date">
                <x-slot name="trigger">
                    <flux:date-picker.input />
                </x-slot>
            </flux:date-picker>
            <flux:error name="form.birth_date" />
        </flux:field>

        <flux:field>
            <flux:label>MRN Number</flux:label>
            <flux:input wire:model="form.mrn" />
            <flux:error name="form.mrn" />
        </flux:field>

        <flux:field>
            <flux:label>Unit</flux:label>
            <flux:input wire:model="form.unit" />
            <flux:error name="form.unit" />
        </flux:field>

        <flux:field>
            <flux:label>Room Number</flux:label>
            <flux:input wire:model="form.room" />
            <flux:error name="form.room" />
        </flux:field>

        <flux:field>
            <flux:label>Admission Date</flux:label>
            <flux:date-picker max="today" wire:model="form.admission_date" />
            <flux:error name="form.admission_date" />
        </flux:field>

        <flux:field>
            <flux:label>Attending MD</flux:label>
            <flux:input wire:model="form.attending_md" />
            <flux:error name="form.attending_md" />
        </flux:field>

        <flux:field>
            <flux:label>Diagnosis</flux:label>
            <flux:input wire:model="form.diagnosis" />
            <flux:error name="form.diagnosis" />
        </flux:field>

        <flux:field>
            <flux:label>Diet Order</flux:label>
            <flux:input wire:model="form.diet_order" />
            <flux:error name="form.diet_order" />
        </flux:field>

        <flux:field>
            <flux:label>Activity Level</flux:label>
            <flux:input wire:model="form.activity_level" />
            <flux:error name="form.activity_level" />
        </flux:field>

        <flux:field>
            <flux:label>Procedure</flux:label>
            <flux:input wire:model="form.procedure" />
            <flux:error name="form.procedure" />
        </flux:field>

        <flux:field>
            <flux:label>Status</flux:label>
            <flux:input wire:model="form.status" />
            <flux:error name="form.status" />
        </flux:field>

        <flux:field>
            <flux:label>Isolation</flux:label>
            <flux:input wire:model="form.isolation" />
            <flux:error name="form.isolation" />
        </flux:field>

        <flux:button type="submit" variant="primary">Submit</flux:button>
        <flux:button wire:navigate href="{{ route('patient.index') }}" variant="ghost">Cancel</flux:button>
    </form>
</div>
