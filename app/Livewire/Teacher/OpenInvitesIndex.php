<?php

declare(strict_types=1);

namespace App\Livewire\Teacher;

use App\Models\Invitation;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

final class OpenInvitesIndex extends Component
{
    use WithPagination;

    public $sortBy = 'name';

    public $sortDirection = 'desc';

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
    public function invitations()
    {
        return Invitation::query()
            ->tap(fn ($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->select(['id', 'email', 'role', 'role'])
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.teacher.open-invites-index');
    }
}
