<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Models\Vital;
use Flux\Flux;
use Illuminate\View\View;
use Livewire\Component;

final class Delete extends Component
{
    public Vital $vital;

    public function delete(): void
    {
        $this->vital->update(['deleted_by' => auth()->id()]);
        $this->vital->delete();

        $this->dispatch('vital-deleted');

        Flux::toast(text: 'Vital deleted successfully', variant: 'success', position: 'top center');
    }

    public function render(): View
    {
        return view('livewire.forms.delete');
    }
}
