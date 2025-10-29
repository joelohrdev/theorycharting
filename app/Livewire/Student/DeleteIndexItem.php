<?php

declare(strict_types=1);

namespace App\Livewire\Student;

use App\Models\User;
use Flux\Flux;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

final class DeleteIndexItem extends Component
{
    #[Locked]
    public User $user;

    public function restoreStudent(): void
    {
        $this->authorize('restore-student', [User::class, $this->user]);

        $this->user->restore();

        Flux::toast(text: 'Student restored successfully', variant: 'success', position: 'top center');

        $this->dispatch('student-restored');
    }

    public function render(): View
    {
        return view('livewire.student.delete-index-item');
    }
}
