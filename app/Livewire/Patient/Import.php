<?php

declare(strict_types=1);

namespace App\Livewire\Patient;

use App\Imports\PatientsImport;
use App\Models\Patient;
use Exception;
use Flux\Flux;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

final class Import extends Component
{
    use WithFileUploads;

    #[Validate('required|file|mimes:csv,txt|max:10240')]
    public $csvFile;

    public int $importedCount = 0;

    public bool $importing = false;

    public ?string $errorMessage = null;

    public function mount(): void
    {
        if (! auth()->check()) {
            abort(403);
        }
    }

    public function import(): void
    {
        if (! auth()->check()) {
            abort(403);
        }

        $this->validate();

        $this->importing = true;
        $this->errorMessage = null;

        try {
            $import = new PatientsImport(auth()->id());

            Excel::import($import, $this->csvFile->getRealPath());

            $this->importedCount = Patient::where('user_id', auth()->id())->count();

            $this->dispatch('patients-imported');
            $this->dispatch('close-modal', name: 'import-patients');

            $this->reset(['csvFile', 'importing']);

            $this->dispatch('patients-imported');

            $this->modal('import-patients')->close();

            Flux::toast(text: 'Patients imported successfully', variant: 'success', position: 'top center');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];

            foreach ($failures as $failure) {
                $errorMessages[] = "Row {$failure->row()}: ".implode(', ', $failure->errors());
            }

            $this->errorMessage = implode(' | ', array_slice($errorMessages, 0, 3));
            $this->importing = false;
        } catch (Exception $e) {
            $this->errorMessage = 'An error occurred during import: '.$e->getMessage();
            $this->importing = false;
        }
    }

    public function render(): View
    {
        return view('livewire.patient.import');
    }
}
