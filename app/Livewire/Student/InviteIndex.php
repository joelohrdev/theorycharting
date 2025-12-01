<?php

declare(strict_types=1);

namespace App\Livewire\Student;

use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

final class InviteIndex extends Component
{
    use WithPagination;

    public string $sortBy = 'created_at';

    public string $sortDirection = 'desc';

    public function sort($column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    #[Computed]
    #[On('invite-created')]
    #[On('invite-deleted')]
    public function invites()
    {
        return auth()->user()->invitations()
            ->whereNull('accepted_at')
            ->tap(fn ($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->select(['id', 'email', 'created_at'])
            ->orderBy('email')
            ->paginate(10);

    }

    public function render(): View
    {
        return view('livewire.student.invite-index');
    }
}
