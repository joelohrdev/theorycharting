<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\BpMethod;
use App\Enums\BpSource;
use App\Enums\Heart;
use App\Enums\Oxygen;
use App\Enums\PainDescriptors;
use App\Enums\PainScale;
use App\Enums\PatientPosition;
use App\Enums\Temp;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vital>
 */
final class VitalFactory extends Factory
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
            'temperature' => fake()->numberBetween(960, 1020),
            'temperature_source' => fake()->randomElement(Temp::cases()),
            'heart_rate' => fake()->numberBetween(60, 100),
            'heart_rate_source' => fake()->randomElement(Heart::cases()),
            'resp' => fake()->numberBetween(12, 20),
            'systolic' => fake()->numberBetween(100, 140),
            'diastolic' => fake()->numberBetween(60, 90),
            'bp_source' => fake()->randomElement(BpSource::cases()),
            'bp_method' => fake()->randomElement(BpMethod::cases()),
            'patient_position' => fake()->randomElement(PatientPosition::cases()),
            'abdominal_girth' => fake()->numberBetween(70, 120).' cm',
            'pain_scale' => fake()->randomElement(PainScale::cases()),
            'pain_score' => (string) fake()->numberBetween(0, 10),
            'pain_goal' => fake()->numberBetween(0, 5),
            'pain_location' => fake()->randomElement(['Abdomen', 'Chest', 'Back', 'Head', 'Leg', 'Arm']),
            'pain_descriptors' => fake()->randomElements(
                array_map(fn ($case) => $case->value, PainDescriptors::cases()),
                fake()->numberBetween(1, 3)
            ),
            'sp02' => fake()->numberBetween(90, 100),
            'oxygen_device' => fake()->randomElement(Oxygen::cases()),
        ];
    }
}
