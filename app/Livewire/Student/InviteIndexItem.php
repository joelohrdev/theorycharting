<?php

declare(strict_types=1);

namespace App\Livewire\Student;

use App\Mail\InvitationMail;
use App\Models\Invitation;
use Flux\Flux;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

final class InviteIndexItem extends Component
{
    #[Locked]
    public Invitation $invitation;

    public function delete(): void
    {
        $this->authorize('delete', $this->invitation);

        $this->invitation->delete();

        Flux::toast(text: 'Invitation deleted successfully', variant: 'success', position: 'top center');

        $this->dispatch('invite-deleted');
    }

    public function resendInvitation(): void
    {
        $this->authorize('resend-invitation', $this->invitation);

        Mail::to($this->invitation->email)->send(new InvitationMail($this->invitation));

        Flux::toast(text: 'Invitation sent successfully', variant: 'success', position: 'top center');

        $this->dispatch('invite-created');
    }

    public function render(): View
    {
        return view('livewire.student.invite-index-item');
    }
}
