<?php

declare(strict_types=1);

namespace App\Livewire\Teacher;

use App\Models\Invitation;
use Illuminate\View\View;
use Livewire\Component;

final class OpenInvitesIndexItem extends Component
{
    public Invitation $invitation;

    public function render(): View
    {
        return view('livewire.teacher.open-invites-index-item');
    }
}
