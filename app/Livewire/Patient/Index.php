<?php

declare(strict_types=1);

namespace App\Livewire\Patient;

use App\Models\Patient;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

final class Index extends Component
{
    use WithPagination;

    public $sortBy = 'name';

    public $sortDirection = 'asc';

    public string $search = '';

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
    #[On('patients-imported')]
    public function patients()
    {
        return $this->getPatientsQuery()
            ->tap(fn ($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->orderBy('name')
            ->when($this->search, fn ($query) => $query->where('name', 'like', "%{$this->search}%"))
            ->paginate(15);
    }

    public function render(): View
    {
        return view('livewire.patient.index');
    }

    private function getPatientsQuery()
    {
        if (auth()->user()->is_admin) {
            return Patient::query();
        }

        if (auth()->user()->isTeacher()) {
            return Patient::where('teacher_id', auth()->user()->id);
        }

        return Patient::query()
            ->where('user_id', auth()->user()->teacher_id);
    }
}
