<?php

declare(strict_types=1);

namespace App\Livewire\Patient;

use App\Models\Patient;
use Illuminate\View\View;
use Livewire\Component;

final class IntakeOutput extends Component
{
    public Patient $patient;

    public function render(): View
    {
        return view('livewire.patient.intake-output');
    }
}
