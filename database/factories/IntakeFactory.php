<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Appetite;
use App\Enums\EmesisAmount;
use App\Enums\EmesisCharacteristics;
use App\Enums\StoolAmount;
use App\Enums\StoolCharacteristics;
use App\Enums\UnableToEatDrink;
use App\Enums\UrineCharacteristics;
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
        $hasUnableToEatDrink = fake()->boolean(20);
        $hasEmesis = fake()->boolean(15);

        return [
            'patient_id' => Patient::factory(),
            'user_id' => User::factory(),
            'appetite' => [fake()->randomElement(Appetite::cases())->value],
            'appetite_comment' => fake()->boolean(30) ? fake()->sentence() : null,
            'unable_to_eat_drink' => $hasUnableToEatDrink ? [fake()->randomElement(UnableToEatDrink::cases())->value] : [],
            'unable_to_eat_drink_comment' => $hasUnableToEatDrink && fake()->boolean(50) ? fake()->sentence() : null,
            'percentage_eaten' => fake()->numberBetween(0, 100),
            'liquids' => fake()->numberBetween(0, 2000),
            'urine' => fake()->numberBetween(0, 1500),
            'unmeasured_urine_occurrence' => fake()->numberBetween(0, 5),
            'urine_characteristics' => fake()->randomElements(
                array_map(fn ($case) => $case->value, UrineCharacteristics::cases()),
                fake()->numberBetween(1, 3)
            ),
            'urine_characteristics_comment' => fake()->boolean(20) ? fake()->sentence() : null,
            'stool' => fake()->numberBetween(0, 500),
            'stool_amount' => [fake()->randomElement(StoolAmount::cases())->value],
            'stool_amount_comment' => fake()->boolean(20) ? fake()->sentence() : null,
            'unmeasured_stool_occurrence' => fake()->numberBetween(0, 3),
            'stool_characteristics' => fake()->randomElements(
                array_map(fn ($case) => $case->value, StoolCharacteristics::cases()),
                fake()->numberBetween(1, 3)
            ),
            'stool_characteristics_comment' => fake()->boolean(20) ? fake()->sentence() : null,
            'unmeasured_emesis_occurrence' => $hasEmesis ? fake()->numberBetween(1, 3) : 0,
            'emesis_characteristics' => $hasEmesis ? fake()->randomElements(
                array_map(fn ($case) => $case->value, EmesisCharacteristics::cases()),
                fake()->numberBetween(1, 2)
            ) : [],
            'emesis_characteristics_comment' => $hasEmesis && fake()->boolean(40) ? fake()->sentence() : null,
            'emesis_amount' => $hasEmesis ? [fake()->randomElement(EmesisAmount::cases())->value] : [],
            'emesis_amount_comment' => $hasEmesis && fake()->boolean(30) ? fake()->sentence() : null,
        ];
    }
}
