<?php

declare(strict_types=1);

use App\Models\Adl;
use App\Models\Intake;
use App\Models\Patient;
use App\Models\User;
use App\Models\Vital;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Student show page authorization', function () {
    test('teacher can view their own student', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->forTeacher($teacher)->create();

        $response = $this->actingAs($teacher)->get(route('student.show', $student));

        $response->assertOk()
            ->assertSee($student->name)
            ->assertSee($student->email);
    });

    test('teacher cannot view another teachers student', function () {
        $teacher1 = User::factory()->teacher()->create();
        $teacher2 = User::factory()->teacher()->create();
        $student = User::factory()->forTeacher($teacher2)->create();

        $response = $this->actingAs($teacher1)->get(route('student.show', $student));

        $response->assertForbidden();
    });

    test('student cannot view their own page', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->forTeacher($teacher)->create();

        $response = $this->actingAs($student)->get(route('student.show', $student));

        $response->assertForbidden();
    });

    test('guest cannot view student page', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->forTeacher($teacher)->create();

        $response = $this->get(route('student.show', $student));

        $response->assertRedirect(route('login'));
    });
});

describe('Student show page displays forms', function () {
    test('displays vitals forms completed by student', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->forTeacher($teacher)->create();
        $patient = Patient::factory()->for($student, 'user')->create();

        $vital = Vital::factory()->for($patient)->for($student, 'user')->create([
            'temperature' => 98,
            'heart_rate' => 72,
            'systolic' => 120,
            'diastolic' => 80,
            'sp02' => 98,
        ]);

        $response = $this->actingAs($teacher)->get(route('student.show', $student));

        $response->assertOk()
            ->assertSee('Vital Signs')
            ->assertSee($patient->name)
            ->assertSee('98°F')
            ->assertSee('72')
            ->assertSee('120/80')
            ->assertSee('98%');
    });

    test('displays intake forms completed by student', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->forTeacher($teacher)->create();
        $patient = Patient::factory()->for($student, 'user')->create();

        $intake = Intake::factory()->for($patient)->for($student, 'user')->create([
            'appetite' => ['Good'],
            'percentage_eaten' => 75,
            'liquids' => 500,
            'urine' => 400,
        ]);

        $response = $this->actingAs($teacher)->get(route('student.show', $student));

        $response->assertOk()
            ->assertSee('Intake')
            ->assertSee('Output')
            ->assertSee($patient->name)
            ->assertSee('Good')
            ->assertSee('75%')
            ->assertSee('500')
            ->assertSee('400');
    });

    test('displays adl forms completed by student', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->forTeacher($teacher)->create();
        $patient = Patient::factory()->for($student, 'user')->create();

        $adl = Adl::factory()->for($patient)->for($student, 'user')->create([
            'bathing' => ['Complete Bath'],
            'oral_care' => ['Brushed Teeth'],
            'activity' => ['Walking'],
            'mobility' => ['Independent'],
        ]);

        $response = $this->actingAs($teacher)->get(route('student.show', $student));

        $response->assertOk()
            ->assertSee('Activities of Daily Living')
            ->assertSee($patient->name)
            ->assertSee('Complete Bath')
            ->assertSee('Brushed Teeth')
            ->assertSee('Walking')
            ->assertSee('Independent');
    });

    test('displays total form count', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->forTeacher($teacher)->create();
        $patient = Patient::factory()->for($student, 'user')->create();

        Vital::factory()->count(2)->for($patient)->for($student, 'user')->create();
        Intake::factory()->count(3)->for($patient)->for($student, 'user')->create();
        Adl::factory()->count(1)->for($patient)->for($student, 'user')->create();

        $response = $this->actingAs($teacher)->get(route('student.show', $student));

        $response->assertOk()
            ->assertSee('6 Total Forms');
    });

    test('displays message when student has no forms', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->forTeacher($teacher)->create();

        $response = $this->actingAs($teacher)->get(route('student.show', $student));

        $response->assertOk()
            ->assertSee('This student has not completed any forms yet');
    });

    test('does not display forms from other students', function () {
        $teacher = User::factory()->teacher()->create();
        $student1 = User::factory()->forTeacher($teacher)->create(['name' => 'Student One']);
        $student2 = User::factory()->forTeacher($teacher)->create(['name' => 'Student Two']);

        $patient1 = Patient::factory()->for($student1, 'user')->create(['name' => 'Patient One']);
        $patient2 = Patient::factory()->for($student2, 'user')->create(['name' => 'Patient Two']);

        Vital::factory()->for($patient1)->for($student1, 'user')->create();
        Vital::factory()->for($patient2)->for($student2, 'user')->create();

        $response = $this->actingAs($teacher)->get(route('student.show', $student1));

        $response->assertOk()
            ->assertSee('Patient One')
            ->assertDontSee('Patient Two');
    });
});

describe('Student show page form ordering', function () {
    test('displays forms in latest first order', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->forTeacher($teacher)->create();
        $patient = Patient::factory()->for($student, 'user')->create();

        $vital1 = Vital::factory()->for($patient)->for($student, 'user')->create([
            'created_at' => now()->subDays(2),
        ]);

        $vital2 = Vital::factory()->for($patient)->for($student, 'user')->create([
            'created_at' => now()->subDay(),
        ]);

        $vital3 = Vital::factory()->for($patient)->for($student, 'user')->create([
            'created_at' => now(),
        ]);

        $response = $this->actingAs($teacher)->get(route('student.show', $student));

        $response->assertOk();

        $content = $response->getContent();
        $pos1 = mb_strpos($content, $vital3->created_at->format('M d, Y'));
        $pos2 = mb_strpos($content, $vital2->created_at->format('M d, Y'));
        $pos3 = mb_strpos($content, $vital1->created_at->format('M d, Y'));

        expect($pos1)->toBeLessThan($pos2);
        expect($pos2)->toBeLessThan($pos3);
    });
});
