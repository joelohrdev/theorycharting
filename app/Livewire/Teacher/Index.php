<?php

declare(strict_types=1);

namespace App\Livewire\Teacher;

use App\Models\User;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

final class Index extends Component
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
    public function teachers()
    {
        return User::query()
            ->tap(fn ($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->where('role', 'teacher')
            ->select(['id', 'name', 'email', 'is_admin', 'role'])
            ->orderBy('name')
            ->paginate(10);
    }

    public function render(): View
    {
        return view('livewire.teacher.index');
    }
}
