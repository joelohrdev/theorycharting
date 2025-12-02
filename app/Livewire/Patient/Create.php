<?php

declare(strict_types=1);

namespace App\Livewire\Patient;

use App\Livewire\Forms\PatientCreateForm;
use Flux\Flux;
use Illuminate\View\View;
use Livewire\Component;

final class Create extends Component
{
    public PatientCreateForm $form;

    public function mount(): void
    {
        if (! auth()->check()) {
            abort(403);
        }

        if (auth()->user()->isStudent()) {
            abort(403);
        }
    }

    public function create(): void
    {
        if (! auth()->check()) {
            abort(403);
        }

        if (auth()->user()->isStudent()) {
            abort(403);
        }

        $this->form->validate();

        $user = auth()->user();

        $user->patients()->create([
            'name' => $this->form->name,
            'gender' => $this->form->gender,
            'birth_date' => $this->form->birth_date,
            'mrn' => $this->form->mrn,
            'room' => $this->form->room,
            'admission_date' => $this->form->admission_date,
            'attending_md' => $this->form->attending_md,
            'diagnosis' => $this->form->diagnosis,
            'diet_order' => $this->form->diet_order,
            'activity_level' => $this->form->activity_level,
            'procedure' => $this->form->procedure,
            'status' => $this->form->status,
            'isolation' => $this->form->isolation,
            'unit' => $this->form->unit,
        ]);

        $this->reset('form');

        Flux::toast(text: 'Patient record saved successfully', variant: 'success', position: 'top center');
    }

    public function render(): View
    {
        return view('livewire.patient.create');
    }
}
