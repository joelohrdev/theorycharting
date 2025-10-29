<?php

declare(strict_types=1);

namespace App\Livewire\Student;

use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

final class DeleteIndex extends Component
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
    #[On('student-restored')]
    public function deletedStudents()
    {
        return auth()->user()->students()
            ->onlyTrashed()
            ->tap(fn ($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->select(['id', 'name', 'email'])
            ->orderBy('name')
            ->paginate(10);
    }

    public function render(): View
    {
        return view('livewire.student.delete-index');
    }
}
