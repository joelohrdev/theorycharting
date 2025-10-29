<?php

declare(strict_types=1);

namespace App\Livewire\Student;

use App\Livewire\Forms\StudentForm;
use App\Mail\InvitationMail;
use App\Models\Invitation;
use Flux\Flux;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Livewire\Component;

final class Invite extends Component
{
    public StudentForm $form;

    public function sendInvite(): void
    {
        $this->authorize('create-student', Invitation::class);

        $invitation = $this->form->save();

        Mail::to($invitation->email)->send(new InvitationMail($invitation));

        Flux::toast(text: 'Invitation sent successfully', variant: 'success', position: 'top center');

        $this->form->reset();

        $this->dispatch('invite-created');
    }

    public function render(): View
    {
        return view('livewire.student.invite');
    }
}
