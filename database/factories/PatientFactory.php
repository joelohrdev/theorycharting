<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
final class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => Str::uuid()->toString(),
            'user_id' => User::factory(),
            'name' => fake()->name(),
            'gender' => fake()->randomElement(['Male', 'Female', 'Other']),
            'birth_date' => fake()->date(),
            'mrn' => fake()->unique()->numerify('MRN######'),
            'room' => fake()->numerify('###'),
            'admission_date' => fake()->dateTimeBetween('-30 days', 'now'),
            'attending_md' => 'Dr. '.fake()->lastName(),
            'diagnosis' => fake()->sentence(3),
            'diet_order' => fake()->randomElement(['Regular', 'NPO', 'Soft', 'Liquid']),
            'activity_level' => fake()->randomElement(['Ambulatory', 'Bedrest', 'Limited']),
            'procedure' => fake()->sentence(2),
            'status' => fake()->randomElement(['Stable', 'Critical', 'Fair']),
            'isolation' => fake()->randomElement(['None', 'Contact', 'Droplet', 'Airborne']),
            'unit' => fake()->randomElement(['ICU', 'Med-Surg', 'Pediatrics', 'ER']),
        ];
    }
}
