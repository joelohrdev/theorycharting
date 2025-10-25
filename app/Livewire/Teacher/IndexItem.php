<?php

declare(strict_types=1);

namespace App\Livewire\Teacher;

use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;

class IndexItem extends Component
{
    public User $user;

    public function studentCount(): int
    {
        return $this->user->students()->count();
    }

    public function render(): View
    {
        return view('livewire.teacher.index-item');
    }
}
