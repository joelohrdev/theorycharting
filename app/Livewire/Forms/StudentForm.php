<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Enums\Role;
use App\Models\Invitation;
use App\Models\User;
use Livewire\Form;

final class StudentForm extends Form
{
    public string $studentId = '';

    public function save(): Invitation
    {
        $email = $this->studentId.'@student.techcampus.org';

        $this->validate([
            'studentId' => [
                'required',
                'string',
                'not_regex:/[!@#$%^&*()]/',
                function ($attribute, $value, $fail) use ($email) {
                    if (User::where('email', $email)->exists()) {
                        $fail('This student ID is already registered.');
                    }
                    if (Invitation::where('email', $email)->exists()) {
                        $fail('This student ID already has a pending invitation.');
                    }
                },
            ],
        ]);

        return Invitation::create([
            'email' => $email,
            'token' => Invitation::generateToken(),
            'role' => Role::STUDENT,
            'invited_by' => auth()->id(),
            'teacher_id' => auth()->id(),
            'expires_at' => now()->addDays(7),
        ]);
    }
}
