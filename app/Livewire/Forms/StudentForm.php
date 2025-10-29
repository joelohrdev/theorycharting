<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\enums\Role;
use App\Models\Invitation;
use Livewire\Attributes\Rule;
use Livewire\Form;

final class StudentForm extends Form
{
    #[Rule(['required', 'email', 'unique:users,email', 'unique:invitations,email'])]
    public string $email = '';

    public function save(): Invitation
    {
        $this->validate();

        return Invitation::create([
            'email' => $this->email,
            'token' => Invitation::generateToken(),
            'role' => Role::STUDENT,
            'invited_by' => auth()->id(),
            'teacher_id' => auth()->id(),
            'expires_at' => now()->addDays(7),
        ]);
    }
}
