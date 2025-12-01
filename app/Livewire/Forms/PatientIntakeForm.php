<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Enums\Appetite;
use App\Enums\EmesisAmount;
use App\Enums\EmesisCharacteristics;
use App\Enums\StoolAmount;
use App\Enums\StoolCharacteristics;
use App\Enums\UnableToEatDrink;
use App\Enums\UrineCharacteristics;
use App\Models\Intake;
use App\Models\Patient;
use Illuminate\Validation\Rule;
use Livewire\Form;

final class PatientIntakeForm extends Form
{
    public ?Intake $intake = null;

    public array $appetite = [];

    public string $appetiteComment = '';

    public array $unableToEatDrink = [];

    public string $unableToEatDrinkComment = '';

    public ?int $percentageEaten = null;

    public ?int $liquids = null;

    public ?int $urine = null;

    public ?int $unmeasuredUrineOccurrence = null;

    public array $urineCharacteristics = [];

    public string $urineCharacteristicsComment = '';

    public ?int $stool = null;

    public array $stoolAmount = [];

    public string $stoolAmountComment = '';

    public ?int $unmeasuredStoolOccurrence = null;

    public array $stoolCharacteristics = [];

    public string $stoolCharacteristicsComment = '';

    public ?int $unmeasuredEmesisOccurrence = null;

    public array $emesisCharacteristics = [];

    public string $emesisCharacteristicsComment = '';

    public array $emesisAmount = [];

    public string $emesisAmountComment = '';

    public function setIntake(Intake $intake): void
    {
        $this->intake = $intake;
        $this->appetite = $intake->appetite ?? [];
        $this->appetiteComment = $intake->appetite_comment ?? '';
        $this->unableToEatDrink = $intake->unable_to_eat_drink ?? [];
        $this->unableToEatDrinkComment = $intake->unable_to_eat_drink_comment ?? '';
        $this->percentageEaten = $intake->percentage_eaten ?? null;
        $this->liquids = $intake->liquids ?? null;
        $this->urine = $intake->urine ?? null;
        $this->unmeasuredUrineOccurrence = $intake->unmeasured_urine_occurrence ?? null;
        $this->urineCharacteristics = $intake->urine_characteristics ?? [];
        $this->urineCharacteristicsComment = $intake->urine_characteristics_comment ?? '';
        $this->stool = $intake->stool ?? null;
        $this->stoolAmount = $intake->stool_amount ?? [];
        $this->stoolAmountComment = $intake->stool_amount_comment ?? '';
        $this->unmeasuredStoolOccurrence = $intake->unmeasured_stool_occurrence ?? null;
        $this->stoolCharacteristics = $intake->stool_characteristics ?? [];
        $this->stoolCharacteristicsComment = $intake->stool_characteristics_comment ?? '';
        $this->unmeasuredEmesisOccurrence = $intake->unmeasured_emesis_occurrence ?? null;
        $this->emesisCharacteristics = $intake->emesis_characteristics ?? [];
        $this->emesisCharacteristicsComment = $intake->emesis_characteristics_comment ?? '';
        $this->emesisAmount = $intake->emesis_amount ?? [];
        $this->emesisAmountComment = $intake->emesis_amount_comment ?? '';
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'appetite' => ['nullable', 'array'],
            'appetite.*' => ['string', Rule::enum(Appetite::class)],
            'appetiteComment' => ['nullable', 'string', 'max:1000'],
            'unableToEatDrink' => ['nullable', 'array'],
            'unableToEatDrink.*' => ['string', Rule::enum(UnableToEatDrink::class)],
            'unableToEatDrinkComment' => ['nullable', 'string', 'max:1000'],
            'percentageEaten' => ['nullable', 'integer', 'min:0', 'max:100'],
            'liquids' => ['nullable', 'integer', 'min:0'],
            'urine' => ['nullable', 'integer', 'min:0'],
            'unmeasuredUrineOccurrence' => ['nullable', 'integer', 'min:0'],
            'urineCharacteristics' => ['nullable', 'array'],
            'urineCharacteristics.*' => ['string', Rule::enum(UrineCharacteristics::class)],
            'urineCharacteristicsComment' => ['nullable', 'string', 'max:1000'],
            'stool' => ['nullable', 'integer', 'min:0'],
            'stoolAmount' => ['nullable', 'array'],
            'stoolAmount.*' => ['string', Rule::enum(StoolAmount::class)],
            'stoolAmountComment' => ['nullable', 'string', 'max:1000'],
            'unmeasuredStoolOccurrence' => ['nullable', 'integer', 'min:0'],
            'stoolCharacteristics' => ['nullable', 'array'],
            'stoolCharacteristics.*' => ['string', Rule::enum(StoolCharacteristics::class)],
            'stoolCharacteristicsComment' => ['nullable', 'string', 'max:1000'],
            'unmeasuredEmesisOccurrence' => ['nullable', 'integer', 'min:0'],
            'emesisCharacteristics' => ['nullable', 'array'],
            'emesisCharacteristics.*' => ['string', Rule::enum(EmesisCharacteristics::class)],
            'emesisCharacteristicsComment' => ['nullable', 'string', 'max:1000'],
            'emesisAmount' => ['nullable', 'array'],
            'emesisAmount.*' => ['string', Rule::enum(EmesisAmount::class)],
            'emesisAmountComment' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function create(Patient $patient): Intake
    {
        $this->validate();

        return Intake::create([
            'patient_id' => $patient->id,
            'user_id' => auth()->id(),
            'appetite' => $this->appetite ?: null,
            'appetite_comment' => $this->appetiteComment ?: null,
            'unable_to_eat_drink' => $this->unableToEatDrink ?: null,
            'unable_to_eat_drink_comment' => $this->unableToEatDrinkComment ?: null,
            'percentage_eaten' => $this->percentageEaten,
            'liquids' => $this->liquids,
            'urine' => $this->urine,
            'unmeasured_urine_occurrence' => $this->unmeasuredUrineOccurrence,
            'urine_characteristics' => $this->urineCharacteristics ?: null,
            'urine_characteristics_comment' => $this->urineCharacteristicsComment ?: null,
            'stool' => $this->stool,
            'stool_amount' => $this->stoolAmount ?: null,
            'stool_amount_comment' => $this->stoolAmountComment ?: null,
            'unmeasured_stool_occurrence' => $this->unmeasuredStoolOccurrence,
            'stool_characteristics' => $this->stoolCharacteristics ?: null,
            'stool_characteristics_comment' => $this->stoolCharacteristicsComment ?: null,
            'unmeasured_emesis_occurrence' => $this->unmeasuredEmesisOccurrence,
            'emesis_characteristics' => $this->emesisCharacteristics ?: null,
            'emesis_characteristics_comment' => $this->emesisCharacteristicsComment ?: null,
            'emesis_amount' => $this->emesisAmount ?: null,
            'emesis_amount_comment' => $this->emesisAmountComment ?: null,
        ]);
    }

    public function update(): void
    {
        $this->validate();

        $this->intake->update([
            'appetite' => $this->appetite ?: null,
            'appetite_comment' => $this->appetiteComment ?: null,
            'unable_to_eat_drink' => $this->unableToEatDrink ?: null,
            'unable_to_eat_drink_comment' => $this->unableToEatDrinkComment ?: null,
            'percentage_eaten' => $this->percentageEaten,
            'liquids' => $this->liquids,
            'urine' => $this->urine,
            'unmeasured_urine_occurrence' => $this->unmeasuredUrineOccurrence,
            'urine_characteristics' => $this->urineCharacteristics ?: null,
            'urine_characteristics_comment' => $this->urineCharacteristicsComment ?: null,
            'stool' => $this->stool,
            'stool_amount' => $this->stoolAmount ?: null,
            'stool_amount_comment' => $this->stoolAmountComment ?: null,
            'unmeasured_stool_occurrence' => $this->unmeasuredStoolOccurrence,
            'stool_characteristics' => $this->stoolCharacteristics ?: null,
            'stool_characteristics_comment' => $this->stoolCharacteristicsComment ?: null,
            'unmeasured_emesis_occurrence' => $this->unmeasuredEmesisOccurrence,
            'emesis_characteristics' => $this->emesisCharacteristics ?: null,
            'emesis_characteristics_comment' => $this->emesisCharacteristicsComment ?: null,
            'emesis_amount' => $this->emesisAmount ?: null,
            'emesis_amount_comment' => $this->emesisAmountComment ?: null,
        ]);
    }
}
