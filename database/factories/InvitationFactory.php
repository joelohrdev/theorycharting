<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invitation>
 */
final class InvitationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'token' => \App\Models\Invitation::generateToken(),
            'role' => \App\enums\Role::STUDENT,
            'invited_by' => \App\Models\User::factory()->teacher(),
            'teacher_id' => fn (array $attributes) => $attributes['invited_by'],
            'expires_at' => now()->addDays(7),
            'accepted_at' => null,
        ];
    }

    /**
     * Indicate that the invitation has been accepted.
     */
    public function accepted(): static
    {
        return $this->state(fn (array $attributes) => [
            'accepted_at' => now(),
        ]);
    }

    /**
     * Indicate that the invitation is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => now()->subDays(1),
        ]);
    }

    /**
     * Set a specific teacher for the invitation.
     */
    public function forTeacher(int|\App\Models\User $teacher): static
    {
        $teacherId = $teacher instanceof \App\Models\User ? $teacher->id : $teacher;

        return $this->state(fn (array $attributes) => [
            'invited_by' => $teacherId,
            'teacher_id' => $teacherId,
        ]);
    }
}
