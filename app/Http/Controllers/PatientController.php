<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Patient;

final class PatientController extends Controller
{
    public function show(Patient $patient)
    {
        return view('patient.show', [
            'patient' => $patient,
        ]);
    }
}
