<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Enums\BpMethod;
use App\Enums\BpSource;
use App\Enums\Heart;
use App\Enums\Oxygen;
use App\Enums\PainDescriptors;
use App\Enums\PainScale;
use App\Enums\PatientPosition;
use App\Enums\Temp;
use App\Models\Patient;
use App\Models\Vital;
use Illuminate\Validation\Rule;
use Livewire\Form;

final class PatientVitalsForm extends Form
{
    public ?Vital $vital = null;

    public ?float $temperature = null;

    public string $temperatureSource = '';

    public ?int $heartRate = null;

    public string $heartRateSource = '';

    public ?int $resp = null;

    public ?int $systolic = null;

    public ?int $diastolic = null;

    public string $bpSource = '';

    public string $bpMethod = '';

    public string $patientPosition = '';

    public string $abdominalGirth = '';

    public string $painScale = '';

    public string $painScore = '';

    public ?int $painGoal = null;

    public string $painLocation = '';

    public array $painDescriptors = [];

    public ?int $sp02 = null;

    public string $oxygenDevice = '';

    public function setVital(Vital $vital): void
    {
        $this->vital = $vital;
        $this->temperature = $vital->temperature ? $vital->temperature / 10 : null;
        $this->temperatureSource = $vital->temperature_source?->value ?? '';
        $this->heartRate = $vital->heart_rate ?? 0;
        $this->heartRateSource = $vital->heart_rate_source?->value ?? '';
        $this->resp = $vital->resp ?? 0;
        $this->systolic = $vital->systolic ?? 0;
        $this->diastolic = $vital->diastolic ?? 0;
        $this->bpSource = $vital->bp_source?->value ?? '';
        $this->bpMethod = $vital->bp_method?->value ?? '';
        $this->patientPosition = $vital->patient_position?->value ?? '';
        $this->abdominalGirth = $vital->abdominal_girth ?? '';
        $this->painScale = $vital->pain_scale?->value ?? '';
        $this->painScore = $vital->pain_score ?? '';
        $this->painGoal = $vital->pain_goal ?? 0;
        $this->painLocation = $vital->pain_location ?? '';
        $this->painDescriptors = $vital->pain_descriptors ?? [];
        $this->sp02 = $vital->sp02 ?? 0;
        $this->oxygenDevice = $vital->oxygen_device?->value ?? '';
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'temperature' => ['nullable', 'numeric', 'min:50', 'max:115'],
            'temperatureSource' => ['nullable', 'string', 'exclude_if:temperatureSource,', Rule::enum(Temp::class)],
            'heartRate' => ['nullable', 'integer', 'min:1', 'max:300'],
            'heartRateSource' => ['nullable', 'string', 'exclude_if:heartRateSource,', Rule::enum(Heart::class)],
            'resp' => ['nullable', 'integer', 'min:1', 'max:100'],
            'systolic' => ['nullable', 'integer', 'min:1', 'max:300'],
            'diastolic' => ['nullable', 'integer', 'min:1', 'max:200'],
            'bpSource' => ['nullable', 'string', 'exclude_if:bpSource,', Rule::enum(BpSource::class)],
            'bpMethod' => ['nullable', 'string', 'exclude_if:bpMethod,', Rule::enum(BpMethod::class)],
            'patientPosition' => ['nullable', 'string', 'exclude_if:patientPosition,', Rule::enum(PatientPosition::class)],
            'abdominalGirth' => ['nullable', 'string', 'max:255'],
            'painScale' => ['nullable', 'string', 'exclude_if:painScale,', Rule::enum(PainScale::class)],
            'painScore' => ['nullable', 'string', 'max:255'],
            'painGoal' => ['nullable', 'integer', 'min:0', 'max:10'],
            'painLocation' => ['nullable', 'string', 'max:255'],
            'painDescriptors' => ['nullable', 'array'],
            'painDescriptors.*' => ['string', Rule::enum(PainDescriptors::class)],
            'sp02' => ['nullable', 'integer', 'min:0', 'max:100'],
            'oxygenDevice' => ['nullable', 'string', 'exclude_if:oxygenDevice,', Rule::enum(Oxygen::class)],
        ];
    }

    public function create(Patient $patient): Vital
    {
        $this->validate();

        return Vital::create([
            'patient_id' => $patient->id,
            'user_id' => auth()->id(),
            'temperature' => $this->temperature ? (int) ($this->temperature * 10) : null,
            'temperature_source' => $this->temperatureSource ?: null,
            'heart_rate' => $this->heartRate ?: null,
            'heart_rate_source' => $this->heartRateSource ?: null,
            'resp' => $this->resp ?: null,
            'systolic' => $this->systolic ?: null,
            'diastolic' => $this->diastolic ?: null,
            'bp_source' => $this->bpSource ?: null,
            'bp_method' => $this->bpMethod ?: null,
            'patient_position' => $this->patientPosition ?: null,
            'abdominal_girth' => $this->abdominalGirth ?: null,
            'pain_scale' => $this->painScale ?: null,
            'pain_score' => $this->painScore ?: null,
            'pain_goal' => $this->painGoal ?: null,
            'pain_location' => $this->painLocation ?: null,
            'pain_descriptors' => $this->painDescriptors ?: null,
            'sp02' => $this->sp02 !== 0 ? $this->sp02 : null,
            'oxygen_device' => $this->oxygenDevice ?: null,
        ]);
    }

    public function update(): void
    {
        $this->validate();

        $this->vital->update([
            'temperature' => $this->temperature ?: null,
            'temperature_source' => $this->temperatureSource ?: null,
            'heart_rate' => $this->heartRate ?: null,
            'heart_rate_source' => $this->heartRateSource ?: null,
            'resp' => $this->resp ?: null,
            'systolic' => $this->systolic ?: null,
            'diastolic' => $this->diastolic ?: null,
            'bp_source' => $this->bpSource ?: null,
            'bp_method' => $this->bpMethod ?: null,
            'patient_position' => $this->patientPosition ?: null,
            'abdominal_girth' => $this->abdominalGirth ?: null,
            'pain_scale' => $this->painScale ?: null,
            'pain_score' => $this->painScore ?: null,
            'pain_goal' => $this->painGoal ?: null,
            'pain_location' => $this->painLocation ?: null,
            'pain_descriptors' => $this->painDescriptors ?: null,
            'sp02' => $this->sp02 !== 0 ? $this->sp02 : null,
            'oxygen_device' => $this->oxygenDevice ?: null,
        ]);
    }
}
