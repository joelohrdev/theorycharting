<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Adl;
use App\Models\Intake;
use App\Models\Patient;
use App\Models\User;
use App\Models\Vital;
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

        $me = User::factory()->teacher()->create([
            'name' => 'Me',
            'email' => 'joe.lohr@outlook.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
        ]);

        // Create students for $me
        $students = User::factory()
            ->forTeacher($me)
            ->count(5)
            ->create();

        // Create patients for $me
        $patients = Patient::factory()
            ->count(8)
            ->create(['user_id' => $me->id]);

        // Create form submissions for each student-patient combination
        $students->each(function ($student) use ($patients) {
            // Each student submits forms for 2-3 random patients
            $assignedPatients = $patients->random(random_int(2, 3));

            $assignedPatients->each(function ($patient) use ($student) {
                // Create 1-3 vitals submissions per student-patient pair
                Vital::factory()
                    ->count(random_int(1, 3))
                    ->create([
                        'patient_id' => $patient->id,
                        'user_id' => $student->id,
                    ]);

                // Create 1-2 intake submissions per student-patient pair
                Intake::factory()
                    ->count(random_int(1, 2))
                    ->create([
                        'patient_id' => $patient->id,
                        'user_id' => $student->id,
                    ]);

                // Create 1-2 ADL submissions per student-patient pair
                Adl::factory()
                    ->count(random_int(1, 2))
                    ->create([
                        'patient_id' => $patient->id,
                        'user_id' => $student->id,
                    ]);
            });
        });

        // Create teachers and students for each teacher
        $teachers = User::factory()->teacher()->count(5)->create();

        $teachers->each(function ($teacher) {
            // Create 3-8 students for each teacher
            User::factory()
                ->forTeacher($teacher)
                ->count(random_int(3, 8))
                ->create();
        });
    }
}
