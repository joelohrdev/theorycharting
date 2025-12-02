<div>
    <flux:modal.trigger name="import-patients">
        <flux:button size="sm" variant="primary" icon="arrow-up-tray">Import Patients</flux:button>
    </flux:modal.trigger>

    <flux:modal name="import-patients" class="md:w-96">
        <form wire:submit="import" class="space-y-6">
            <div>
                <flux:heading size="lg">Import Patients</flux:heading>
                <flux:text>Upload a CSV file with patient data. The CSV must include headers matching the required fields.</flux:text>
            </div>

            <div>
                <flux:input
                    type="file"
                    wire:model="csvFile"
                    accept=".csv,.txt"
                    label="Select CSV File"
                />
                @error('csvFile')
                    <flux:text variant="danger" size="sm">{{ $message }}</flux:text>
                @enderror
            </div>

            @if ($csvFile)
                <flux:callout variant="info">
                    <strong>File selected:</strong> {{ $csvFile->getClientOriginalName() }}
                    ({{ number_format($csvFile->getSize() / 1024, 2) }} KB)
                </flux:callout>
            @endif

            @if ($errorMessage)
                <flux:callout variant="danger">
                    <strong>Import failed:</strong> {{ $errorMessage }}
                </flux:callout>
            @endif

            <div>
                <flux:callout variant="warning">
                    <strong>Required CSV columns:</strong>
                    <ul class="mt-2 list-disc pl-5 text-sm">
                        <li>name, gender, birth_date, mrn, room</li>
                        <li>admission_date, attending_md, diagnosis</li>
                        <li>diet_order, activity_level, procedure</li>
                        <li>status, isolation, unit</li>
                    </ul>
                </flux:callout>
            </div>

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary" :disabled="!$csvFile || $importing">
                    <span wire:loading.remove wire:target="import">Import Patients</span>
                    <span wire:loading wire:target="import">Importing...</span>
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
