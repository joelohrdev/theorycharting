<?php

declare(strict_types=1);

namespace App\Livewire\Teacher;

use App\enums\Role;
use App\Mail\InvitationMail;
use App\Models\Invitation;
use Flux;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;

final class Invite extends Component
{
    #[Rule(['string', 'email', 'unique:users,email', 'unique:invitations,email'])]
    public string $email = '';

    public bool $isAdmin = false;

    public function sendInvite(): void
    {
        $this->authorize('create', Invitation::class);

        $this->validate();

        $invitation = Invitation::create([
            'email' => $this->email,
            'token' => Invitation::generateToken(),
            'role' => Role::TEACHER,
            'invited_by' => auth()->id(),
            'expires_at' => now()->addDays(7),
        ]);

        Mail::to($this->email)->send(new InvitationMail($invitation));

        Flux::toast(variant: 'success', position: 'top center', text: 'Invitation sent successfully');

        Flux::modal('invite-teacher')->close();

        $this->reset(['email']);
    }

    public function render(): View
    {
        return view('livewire.teacher.invite');
    }
}
