<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Patient;
use Str;

final class PatientObserver
{
    public function creating(Patient $patient): void
    {
        $patient->uuid = (string) Str::uuid();
    }
}
