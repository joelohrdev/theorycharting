<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Intake>
 */
final class IntakeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'user_id' => User::factory(),
            'appetite' => [],
            'appetite_comment' => null,
            'unable_to_eat_drink' => [],
            'unable_to_eat_drink_comment' => null,
            'percentage_eaten' => 0,
            'liquids' => 0,
            'urine' => 0,
            'unmeasured_urine_occurrence' => 0,
            'urine_characteristics' => [],
            'urine_characteristics_comment' => null,
            'stool' => 0,
            'stool_amount' => [],
            'stool_amount_comment' => null,
            'unmeasured_stool_occurrence' => 0,
            'stool_characteristics' => [],
            'stool_characteristics_comment' => null,
            'unmeasured_emesis_occurrence' => 0,
            'emesis_characteristics' => [],
            'emesis_characteristics_comment' => null,
            'emesis_amount' => [],
            'emesis_amount_comment' => null,
        ];
    }
}
