<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->admin()->create([
            'name' => 'Joe Lohr',
            'email' => 'emailme@joelohr.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
        ]);

        // Create teachers and students for each teacher
        $teachers = User::factory()->teacher()->count(5)->create();

        $teachers->each(function ($teacher) {
            // Create 3-8 students for each teacher
            User::factory()
                ->forTeacher($teacher)
                ->count(rand(3, 8))
                ->create();
        });
    }
}
