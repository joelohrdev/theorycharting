<?php

use App\Enums\Activity;
use App\Enums\Appetite;
use App\Enums\Bathing;
use App\Enums\Mobility;
use App\Enums\OralCare;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public User $student;

    #[Computed]
    public function vitals()
    {
        return $this->student->vitals()
            ->with(['patient'])
            ->latest()
            ->paginate(10, pageName: 'vitalsPage');
    }

    #[Computed]
    public function intakes()
    {
        return $this->student->intakes()
            ->with(['patient'])
            ->latest()
            ->paginate(10, pageName: 'intakesPage');
    }

    #[Computed]
    public function adls()
    {
        return $this->student->adls()
            ->with(['patient'])
            ->latest()
            ->paginate(10, pageName: 'adlsPage');
    }

    #[Computed]
    public function totalForms(): int
    {
        return $this->student->vitals()->count()
            + $this->student->intakes()->count()
            + $this->student->adls()->count();
    }

    #[Computed]
    public function vitalsCount(): int
    {
        return $this->student->vitals()->count();
    }

    #[Computed]
    public function intakesCount(): int
    {
        return $this->student->intakes()->count();
    }

    #[Computed]
    public function adlsCount(): int
    {
        return $this->student->adls()->count();
    }
}; ?>

<div class="flex h-full w-full flex-1 flex-col gap-6">
    <div class="flex items-center justify-between">
        <flux:badge size="lg">{{ $this->totalForms() }} Total Forms</flux:badge>
    </div>

    @if($this->totalForms() === 0)
        <flux:callout variant="info">
            This student has not completed any forms yet.
        </flux:callout>
    @else
        <flux:tab.group>
            <flux:tabs>
                @if($this->vitalsCount() > 0)
                    <flux:tab name="vitals">Vital Signs ({{ $this->vitalsCount() }})</flux:tab>
                @endif

                @if($this->intakesCount() > 0)
                    <flux:tab name="intakes">Intake & Output ({{ $this->intakesCount() }})</flux:tab>
                @endif

                @if($this->adlsCount() > 0)
                    <flux:tab name="adls">Activities of Daily Living ({{ $this->adlsCount() }})</flux:tab>
                @endif
            </flux:tabs>

            @if($this->vitalsCount() > 0)
                <flux:tab.panel name="vitals">
                    <flux:table :paginate="$this->vitals()">
                        <flux:table.columns>
                            <flux:table.column>Patient</flux:table.column>
                            <flux:table.column>Date</flux:table.column>
                            <flux:table.column>Temperature</flux:table.column>
                            <flux:table.column>Heart Rate</flux:table.column>
                            <flux:table.column>Blood Pressure</flux:table.column>
                            <flux:table.column>SpO2</flux:table.column>
                        </flux:table.columns>

                        <flux:table.rows>
                            @foreach($this->vitals() as $vital)
                                <flux:table.row>
                                    <flux:table.cell>{{ $vital->patient->name }}</flux:table.cell>
                                    <flux:table.cell>{{ $vital->created_at->format('M d, Y g:i A') }}</flux:table.cell>
                                    <flux:table.cell>{{ $vital->temperature ? $vital->temperature . '°F' : '-' }}</flux:table.cell>
                                    <flux:table.cell>{{ $vital->heart_rate ?? '-' }}</flux:table.cell>
                                    <flux:table.cell>
                                        @if($vital->systolic && $vital->diastolic)
                                            {{ $vital->systolic }}/{{ $vital->diastolic }}
                                        @else
                                            -
                                        @endif
                                    </flux:table.cell>
                                    <flux:table.cell>{{ $vital->sp02 ? $vital->sp02 . '%' : '-' }}</flux:table.cell>
                                </flux:table.row>
                            @endforeach
                        </flux:table.rows>
                    </flux:table>
                </flux:tab.panel>
            @endif

            @if($this->intakesCount() > 0)
                <flux:tab.panel name="intakes">
                    <flux:table :paginate="$this->intakes()">
                        <flux:table.columns>
                            <flux:table.column>Patient</flux:table.column>
                            <flux:table.column>Date</flux:table.column>
                            <flux:table.column>Appetite</flux:table.column>
                            <flux:table.column>% Eaten</flux:table.column>
                            <flux:table.column>Liquids (mL)</flux:table.column>
                            <flux:table.column>Urine (mL)</flux:table.column>
                        </flux:table.columns>

                        <flux:table.rows>
                            @foreach($this->intakes() as $intake)
                                <flux:table.row>
                                    <flux:table.cell>{{ $intake->patient->name }}</flux:table.cell>
                                    <flux:table.cell>{{ $intake->created_at->format('M d, Y g:i A') }}</flux:table.cell>
                                    <flux:table.cell>{{ $intake->appetite ? implode(', ', array_map(fn($value) => Appetite::from($value)->label(), $intake->appetite)) : '-' }}</flux:table.cell>
                                    <flux:table.cell>{{ $intake->percentage_eaten ? $intake->percentage_eaten . '%' : '-' }}</flux:table.cell>
                                    <flux:table.cell>{{ $intake->liquids ?? '-' }}</flux:table.cell>
                                    <flux:table.cell>{{ $intake->urine ?? '-' }}</flux:table.cell>
                                </flux:table.row>
                            @endforeach
                        </flux:table.rows>
                    </flux:table>
                </flux:tab.panel>
            @endif

            @if($this->adlsCount() > 0)
                <flux:tab.panel name="adls">
                    <flux:table :paginate="$this->adls()">
                        <flux:table.columns>
                            <flux:table.column>Patient</flux:table.column>
                            <flux:table.column>Date</flux:table.column>
                            <flux:table.column>Bathing</flux:table.column>
                            <flux:table.column>Oral Care</flux:table.column>
                            <flux:table.column>Activity</flux:table.column>
                            <flux:table.column>Mobility</flux:table.column>
                        </flux:table.columns>

                        <flux:table.rows>
                            @foreach($this->adls() as $adl)
                                <flux:table.row>
                                    <flux:table.cell>{{ $adl->patient->name }}</flux:table.cell>
                                    <flux:table.cell>{{ $adl->created_at->format('M d, Y g:i A') }}</flux:table.cell>
                                    <flux:table.cell>{{ $adl->bathing ? implode(', ', array_map(fn($value) => Bathing::from($value)->label(), $adl->bathing)) : '-' }}</flux:table.cell>
                                    <flux:table.cell>{{ $adl->oral_care ? implode(', ', array_map(fn($value) => OralCare::from($value)->label(), $adl->oral_care)) : '-' }}</flux:table.cell>
                                    <flux:table.cell>{{ $adl->activity ? implode(', ', array_map(fn($value) => Activity::from($value)->label(), $adl->activity)) : '-' }}</flux:table.cell>
                                    <flux:table.cell>{{ $adl->mobility ? implode(', ', array_map(fn($value) => Mobility::from($value)->label(), $adl->mobility)) : '-' }}</flux:table.cell>
                                </flux:table.row>
                            @endforeach
                        </flux:table.rows>
                    </flux:table>
                </flux:tab.panel>
            @endif
        </flux:tab.group>
    @endif
</div>
