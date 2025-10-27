<?php

declare(strict_types=1);

namespace App\Livewire\Teacher;

use App\enums\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Str;

final class Create extends Component
{
    #[Validate('required', 'email', 'unique:users,email')]
    public string $email = '';

    public function create(): void
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make(Str::password()),
            'email_verified_at' => null,
            'role' => Role::Teacher,
        ]);

        $status = Password::sendResetLink(['email' => $user->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->reset(['name', 'email']);
        } else {
            $user->delete();

            $this->addError('email', 'Failed to send password reset link');
        }
    }

    public function render(): View
    {
        return view('livewire.teacher.create');
    }
}
