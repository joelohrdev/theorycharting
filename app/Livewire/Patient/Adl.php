<?php

declare(strict_types=1);

namespace App\Livewire\Patient;

use App\Livewire\Forms\PatientAdlForm;
use App\Models\Adl as AdlModel;
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
final class Adl extends Component
{
    public Patient $patient;

    public PatientAdlForm $form;

    public function save(): void
    {
        try {
            $adl = $this->form->create($this->patient);

            $this->form->reset();

            Flux::toast(text: 'ADL record saved successfully', variant: 'success', position: 'top center');

            $this->dispatch('$refresh');
        } catch (ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            Flux::toast(text: 'Error saving ADL record: '.$e->getMessage(), variant: 'danger', position: 'top center');
            Log::error('Error saving ADL record', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }
    }

    /**
     * @return Collection<int, AdlModel>
     */
    #[On('adl-deleted')]
    #[On('adl-restored')]
    #[Computed]
    public function getAdlsProperty(): Collection
    {
        return $this->patient->adls()->withTrashed()->orderBy('created_at')->get();
    }

    public function render(): View
    {
        return view('livewire.patient.adl');
    }
}
