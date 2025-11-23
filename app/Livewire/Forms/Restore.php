<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Models\Vital;
use Flux\Flux;
use Illuminate\View\View;
use Livewire\Component;

final class Restore extends Component
{
    public Vital $vital;

    public function restore(): void
    {
        $this->vital->update(['deleted_by' => null]);

        $this->vital->restore();

        $this->dispatch('vital-restored');

        Flux::toast(text: 'Vital restored successfully', variant: 'success', position: 'top center');
    }

    public function render(): View
    {
        return view('livewire.forms.restore');
    }
}
