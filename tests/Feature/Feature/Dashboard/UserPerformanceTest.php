<?php

declare(strict_types=1);

use App\Models\Adl;
use App\Models\Intake;
use App\Models\Patient;
use App\Models\User;
use App\Models\Vital;
use Livewire\Livewire;

describe('Dashboard UserPerformance component rendering', function () {
    test('component renders successfully for teacher', function () {
        $teacher = User::factory()->teacher()->create();
        User::factory()->student()->forTeacher($teacher)->create();

        Livewire::actingAs($teacher)
            ->test('dashboard.user-performance')
            ->assertStatus(200)
            ->assertSee('Student Performance');
    });

    test('component shows message for non-teacher users', function () {
        $student = User::factory()->student()->create();

        Livewire::actingAs($student)
            ->test('dashboard.user-performance')
            ->assertStatus(200)
            ->assertSee('Performance Dashboard')
            ->assertSee('This section is only available for teachers');
    });

    test('component displays date range filter when students exist', function () {
        $teacher = User::factory()->teacher()->create();
        User::factory()->student()->forTeacher($teacher)->create();

        Livewire::actingAs($teacher)
            ->test('dashboard.user-performance')
            ->assertSee('Last 7 days')
            ->assertSee('Last 14 days')
            ->assertSee('Last 30 days')
            ->assertSee('Last 90 days');
    });

    test('component shows empty state when no students exist', function () {
        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test('dashboard.user-performance')
            ->assertSee('No Students Yet')
            ->assertSee('Invite Students');
    });
});

describe('Dashboard UserPerformance stats calculation', function () {
    test('displays correct total students count', function () {
        $teacher = User::factory()->teacher()->create();
        User::factory()->count(3)->student()->forTeacher($teacher)->create();

        Livewire::actingAs($teacher)
            ->test('dashboard.user-performance')
            ->assertSee('Total Students')
            ->assertSee('3');
    });

    test('displays correct active students count', function () {
        $teacher = User::factory()->teacher()->create();
        $activeStudent = User::factory()->student()->forTeacher($teacher)->create();
        $inactiveStudent = User::factory()->student()->forTeacher($teacher)->create();

        $patient = Patient::factory()->for($activeStudent, 'user')->create();
        Vital::factory()->for($patient)->for($activeStudent, 'user')->create();

        Livewire::actingAs($teacher)
            ->test('dashboard.user-performance')
            ->assertSee('Active Students')
            ->assertSee('1');
    });

    test('displays correct total entries count', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create();

        $patient = Patient::factory()->for($student, 'user')->create();
        Vital::factory()->count(5)->for($patient)->for($student, 'user')->create();
        Intake::factory()->count(3)->for($patient)->for($student, 'user')->create();
        Adl::factory()->count(2)->for($patient)->for($student, 'user')->create();

        Livewire::actingAs($teacher)
            ->test('dashboard.user-performance')
            ->assertSee('Total Entries')
            ->assertSee('10');
    });

    test('displays correct average entries per student', function () {
        $teacher = User::factory()->teacher()->create();
        $student1 = User::factory()->student()->forTeacher($teacher)->create();
        $student2 = User::factory()->student()->forTeacher($teacher)->create();

        $patient1 = Patient::factory()->for($student1, 'user')->create();
        $patient2 = Patient::factory()->for($student2, 'user')->create();

        Vital::factory()->count(10)->for($patient1)->for($student1, 'user')->create();
        Vital::factory()->count(20)->for($patient2)->for($student2, 'user')->create();

        Livewire::actingAs($teacher)
            ->test('dashboard.user-performance')
            ->assertSee('Avg per Student')
            ->assertSee('15');
    });

    test('only counts entries from selected date range', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create();
        $patient = Patient::factory()->for($student, 'user')->create();

        Vital::factory()->for($patient)->for($student, 'user')->create(['created_at' => now()->subDays(5)]);
        Vital::factory()->for($patient)->for($student, 'user')->create(['created_at' => now()->subDays(20)]);
        Vital::factory()->for($patient)->for($student, 'user')->create(['created_at' => now()->subDays(40)]);

        Livewire::actingAs($teacher)
            ->test('dashboard.user-performance')
            ->set('dateRange', '7')
            ->assertSee('1');
    });
});

describe('Dashboard UserPerformance activity breakdown', function () {
    test('displays activity breakdown with correct counts', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create();
        $patient = Patient::factory()->for($student, 'user')->create();

        Vital::factory()->count(5)->for($patient)->for($student, 'user')->create();
        Intake::factory()->count(3)->for($patient)->for($student, 'user')->create();
        Adl::factory()->count(2)->for($patient)->for($student, 'user')->create();

        Livewire::actingAs($teacher)
            ->test('dashboard.user-performance')
            ->assertSee('Activity Breakdown')
            ->assertSee('Vitals')
            ->assertSee('Intake/Output')
            ->assertSee('ADLs');
    });
});

describe('Dashboard UserPerformance top performers', function () {
    test('displays top 5 performers sorted by total entries', function () {
        $teacher = User::factory()->teacher()->create();

        $students = [];
        for ($i = 1; $i <= 6; $i++) {
            $student = User::factory()->student()->forTeacher($teacher)->create(['name' => "Student Number $i"]);
            $patient = Patient::factory()->for($student, 'user')->create();
            Vital::factory()->count($i)->for($patient)->for($student, 'user')->create();
            $students[] = $student;
        }

        $component = Livewire::actingAs($teacher)
            ->test('dashboard.user-performance');

        $topPerformers = $component->get('topPerformers');

        expect($topPerformers)->toHaveCount(5)
            ->and($topPerformers->first()->name)->toBe('Student Number 6')
            ->and($topPerformers->last()->name)->toBe('Student Number 2');
    });

    test('top performers show correct entry counts', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create(['name' => 'Top Student']);
        $patient = Patient::factory()->for($student, 'user')->create();

        Vital::factory()->count(5)->for($patient)->for($student, 'user')->create();
        Intake::factory()->count(3)->for($patient)->for($student, 'user')->create();
        Adl::factory()->count(2)->for($patient)->for($student, 'user')->create();

        Livewire::actingAs($teacher)
            ->test('dashboard.user-performance')
            ->assertSee('Top Student')
            ->assertSee('5 vitals')
            ->assertSee('3 I/O')
            ->assertSee('2 ADLs')
            ->assertSee('10');
    });
});

describe('Dashboard UserPerformance all students table', function () {
    test('displays all students with their stats', function () {
        $teacher = User::factory()->teacher()->create();
        $student1 = User::factory()->student()->forTeacher($teacher)->create(['name' => 'Alice Student']);
        $student2 = User::factory()->student()->forTeacher($teacher)->create(['name' => 'Bob Student']);

        $patient1 = Patient::factory()->for($student1, 'user')->create();
        $patient2 = Patient::factory()->for($student2, 'user')->create();

        Vital::factory()->count(5)->for($patient1)->for($student1, 'user')->create();
        Intake::factory()->count(3)->for($patient2)->for($student2, 'user')->create();

        Livewire::actingAs($teacher)
            ->test('dashboard.user-performance')
            ->assertSee('All Students')
            ->assertSee('Alice Student')
            ->assertSee('Bob Student')
            ->assertSee($student1->email)
            ->assertSee($student2->email);
    });

    test('only shows teachers own students', function () {
        $teacher1 = User::factory()->teacher()->create();
        $teacher2 = User::factory()->teacher()->create();

        $myStudent = User::factory()->student()->forTeacher($teacher1)->create(['name' => 'My Student']);
        $otherStudent = User::factory()->student()->forTeacher($teacher2)->create(['name' => 'Other Student']);

        Livewire::actingAs($teacher1)
            ->test('dashboard.user-performance')
            ->assertSee('My Student')
            ->assertDontSee('Other Student');
    });

    test('table shows message when no students exist', function () {
        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test('dashboard.user-performance')
            ->assertSee('No Students Yet');
    });
});

describe('Dashboard UserPerformance date range filter', function () {
    test('can change date range to 14 days', function () {
        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test('dashboard.user-performance')
            ->set('dateRange', '14')
            ->assertSet('dateRange', '14');
    });

    test('can change date range to 30 days', function () {
        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test('dashboard.user-performance')
            ->set('dateRange', '30')
            ->assertSet('dateRange', '30');
    });

    test('can change date range to 90 days', function () {
        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test('dashboard.user-performance')
            ->set('dateRange', '90')
            ->assertSet('dateRange', '90');
    });

    test('updates stats when date range changes', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create();
        $patient = Patient::factory()->for($student, 'user')->create();

        Vital::factory()->for($patient)->for($student, 'user')->create(['created_at' => now()->subDays(5)]);
        Vital::factory()->for($patient)->for($student, 'user')->create(['created_at' => now()->subDays(20)]);

        $component = Livewire::actingAs($teacher)
            ->test('dashboard.user-performance');

        $component->set('dateRange', '7');
        expect($component->get('totalEntries'))->toBe(1);

        $component->set('dateRange', '30');
        expect($component->get('totalEntries'))->toBe(2);
    });
});

describe('Dashboard UserPerformance daily activity chart', function () {
    test('displays daily activity chart when students exist', function () {
        $teacher = User::factory()->teacher()->create();
        User::factory()->student()->forTeacher($teacher)->create();

        Livewire::actingAs($teacher)
            ->test('dashboard.user-performance')
            ->assertSee('Daily Activity');
    });

    test('displays weekly activity chart for longer time periods', function () {
        $teacher = User::factory()->teacher()->create();
        User::factory()->student()->forTeacher($teacher)->create();

        Livewire::actingAs($teacher)
            ->test('dashboard.user-performance')
            ->set('dateRange', '30')
            ->assertSee('Weekly Activity');
    });

    test('displays chart with data when students have activity', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create();
        $patient = Patient::factory()->for($student, 'user')->create();

        Vital::factory()->for($patient)->for($student, 'user')->create();

        Livewire::actingAs($teacher)
            ->test('dashboard.user-performance')
            ->assertSee('Daily Activity');
    });
});
