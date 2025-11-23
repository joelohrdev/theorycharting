<?php

declare(strict_types=1);

namespace App\Livewire\Patient;

use App\Livewire\Forms\PatientVitalsForm;
use App\Models\Patient;
use App\Models\Vital;
use Exception;
use Flux\Flux;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Log;

final class VitalsForm extends Component
{
    public Patient $patient;

    public PatientVitalsForm $form;

    public function save(): void
    {
        Log::info('Save method called', [
            'temperature' => $this->form->temperature,
            'heartRate' => $this->form->heartRate,
            'patient_id' => $this->patient->id,
            'user_id' => auth()->id(),
        ]);

        try {
            $vital = $this->form->create($this->patient);

            Log::info('Vital created', ['vital_id' => $vital->id]);

            $this->form->reset();

            Flux::toast(text: 'Vital signs saved successfully', variant: 'success', position: 'top center');

            $this->dispatch('$refresh');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            // Validation errors will be shown automatically by Livewire
            throw $e;
        } catch (Exception $e) {
            Flux::toast(text: 'Error saving vital signs: '.$e->getMessage(), variant: 'danger', position: 'top center');
            Log::error('Error saving vital signs', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }
    }

    /**
     * @return Collection<int, Vital>
     */
    #[On('vital-deleted')]
    #[On('vital-restored')]
    public function getVitalsProperty(): Collection
    {
        return $this->patient->vitals()->withTrashed()->orderBy('created_at')->get();
    }

    public function render(): View
    {
        return view('livewire.patient.vitals-form');
    }
}
