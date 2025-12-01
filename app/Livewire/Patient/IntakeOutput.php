<?php

declare(strict_types=1);

namespace App\Livewire\Patient;

use App\Livewire\Forms\PatientIntakeForm;
use App\Models\Intake;
use App\Models\Patient;
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
final class IntakeOutput extends Component
{
    public Patient $patient;

    public PatientIntakeForm $form;

    public function save(): void
    {
        try {
            $intake = $this->form->create($this->patient);

            $this->form->reset();

            Flux::toast(text: 'Intake/Output saved successfully', variant: 'success', position: 'top center');

            $this->dispatch('$refresh');
        } catch (ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            Flux::toast(text: 'Error saving intake/output: '.$e->getMessage(), variant: 'danger', position: 'top center');
            Log::error('Error saving intake/output', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }
    }

    /**
     * @return Collection<int, Intake>
     */
    #[On('intake-deleted')]
    #[On('intake-restored')]
    #[Computed]
    public function getIntakesProperty(): Collection
    {
        return $this->patient->intakes()->withTrashed()->orderBy('created_at')->get();
    }

    public function render(): View
    {
        return view('livewire.patient.intake-output');
    }
}
