<?php

declare(strict_types=1);

namespace App\Livewire\Student;

use App\Models\User;
use Flux\Flux;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

final class IndexItem extends Component
{
    #[Locked]
    public User $user;

    public function delete(): void
    {
        $this->authorize('delete-student', [User::class, $this->user]);

        $this->user->delete();

        Flux::toast(text: 'Invitation deleted successfully', variant: 'success', position: 'top center');

        $this->dispatch('student-deleted');
    }

    public function render(): View
    {
        return view('livewire.student.index-item');
    }
}
