<?php

declare(strict_types=1);

namespace App\Livewire\Patient;

use App\Livewire\Forms\PatientVitalsForm;
use App\Models\Patient;
use App\Models\Vital;
use Exception;
use Flux\Flux;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Component;
use Log;

#[Lazy]
final class VitalsForm extends Component
{
    public Patient $patient;

    public PatientVitalsForm $form;

    public function save(): void
    {
        try {
            $vital = $this->form->create($this->patient);

            $this->form->reset();

            Flux::toast(text: 'Vital signs saved successfully', variant: 'success', position: 'top center');

            $this->dispatch('$refresh');
        } catch (ValidationException $e) {
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
    #[Computed]
    public function getVitalsProperty(): Collection
    {
        return $this->patient->vitals()->withTrashed()->orderBy('created_at')->get();
    }

    public function render(): View
    {
        return view('livewire.patient.vitals-form');
    }
}
