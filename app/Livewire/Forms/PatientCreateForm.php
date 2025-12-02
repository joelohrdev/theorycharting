<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

final class PatientCreateForm extends Form
{
    #[Validate(['required', 'string', 'max:255'])]
    public $name = '';

    #[Validate(['required', 'string', 'max:255'])]
    public $gender = '';

    #[Validate(['required', 'date', 'before:today'])]
    public $birth_date = '';

    #[Validate(['required', 'string', 'max:255'])]
    public $mrn = '';

    #[Validate(['required', 'string', 'max:255'])]
    public $room = '';

    #[Validate(['required', 'date', 'before:today'])]
    public $admission_date = '';

    #[Validate(['required', 'string', 'max:255'])]
    public $attending_md = '';

    #[Validate(['required', 'string', 'max:255'])]
    public $diagnosis = '';

    #[Validate(['required', 'string', 'max:255'])]
    public $diet_order = '';

    #[Validate(['required', 'string', 'max:255'])]
    public $activity_level = '';

    #[Validate(['required', 'string', 'max:255'])]
    public $procedure = '';

    #[Validate(['required', 'string', 'max:255'])]
    public $status = '';

    #[Validate(['required', 'string', 'max:255'])]
    public $isolation = '';

    #[Validate(['required', 'string', 'max:255'])]
    public $unit = '';
}
