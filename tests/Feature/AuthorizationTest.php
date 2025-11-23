<?php

declare(strict_types=1);

use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Teacher index authorization', function () {
    test('admin can access teacher index', function () {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('teacher.index'));

        $response->assertOk();
    });

    test('teacher cannot access teacher index', function () {
        $teacher = User::factory()->teacher()->create();

        $response = $this->actingAs($teacher)->get(route('teacher.index'));

        $response->assertForbidden();
    });

    test('student cannot access teacher index', function () {
        $student = User::factory()->student()->create();

        $response = $this->actingAs($student)->get(route('teacher.index'));

        $response->assertForbidden();
    });

    test('guest cannot access teacher index', function () {
        $response = $this->get(route('teacher.index'));

        $response->assertRedirect(route('login'));
    });
});

describe('Teacher invites authorization', function () {
    test('admin can access teacher invites', function () {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('teacher.invites'));

        $response->assertOk();
    });

    test('teacher cannot access teacher invites', function () {
        $teacher = User::factory()->teacher()->create();

        $response = $this->actingAs($teacher)->get(route('teacher.invites'));

        $response->assertForbidden();
    });

    test('student cannot access teacher invites', function () {
        $student = User::factory()->student()->create();

        $response = $this->actingAs($student)->get(route('teacher.invites'));

        $response->assertForbidden();
    });

    test('guest cannot access teacher invites', function () {
        $response = $this->get(route('teacher.invites'));

        $response->assertRedirect(route('login'));
    });
});

describe('Student index authorization', function () {
    test('admin can access student index', function () {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('student.index'));

        $response->assertOk();
    });

    test('teacher can access student index', function () {
        $teacher = User::factory()->teacher()->create();

        $response = $this->actingAs($teacher)->get(route('student.index'));

        $response->assertOk();
    });

    test('student cannot access student index', function () {
        $student = User::factory()->student()->create();

        $response = $this->actingAs($student)->get(route('student.index'));

        $response->assertStatus(403);
    });

    test('guest cannot access student index', function () {
        $response = $this->get(route('student.index'));

        $response->assertRedirect(route('login'));
    });
});

describe('Dashboard authorization', function () {
    test('admin can access dashboard', function () {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertOk();
    });

    test('teacher can access dashboard', function () {
        $teacher = User::factory()->teacher()->create();

        $response = $this->actingAs($teacher)->get(route('dashboard'));

        $response->assertOk();
    });

    test('student can access dashboard', function () {
        $student = User::factory()->student()->create();

        $response = $this->actingAs($student)->get(route('dashboard'));

        $response->assertOk();
    });

    test('guest cannot access dashboard', function () {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    });
});

describe('Patient index authorization', function () {
    test('admin can access patient index', function () {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('patient.index'));

        $response->assertOk();
    });

    test('teacher can access patient index', function () {
        $teacher = User::factory()->teacher()->create();

        $response = $this->actingAs($teacher)->get(route('patient.index'));

        $response->assertOk();
    });

    test('student can access patient index', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create();

        $response = $this->actingAs($student)->get(route('patient.index'));

        $response->assertOk();
    });

    test('guest cannot access patient index', function () {
        $response = $this->get(route('patient.index'));

        $response->assertRedirect(route('login'));
    });
});

describe('Patient visibility and filtering', function () {
    test('teacher only sees their own patients', function () {
        $teacher1 = User::factory()->teacher()->create();
        $teacher2 = User::factory()->teacher()->create();

        $teacher1Patients = Patient::factory()->count(3)->create(['user_id' => $teacher1->id]);
        $teacher2Patients = Patient::factory()->count(2)->create(['user_id' => $teacher2->id]);

        $response = $this->actingAs($teacher1)->get(route('patient.index'));

        $response->assertOk();

        // Should see their own patients
        foreach ($teacher1Patients as $patient) {
            $response->assertSee($patient->name);
        }

        // Should NOT see other teacher's patients
        foreach ($teacher2Patients as $patient) {
            $response->assertDontSee($patient->name);
        }
    });

    test('student can only see their teachers patients', function () {
        $teacher = User::factory()->teacher()->create();
        $otherTeacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create();

        $teacherPatients = Patient::factory()->count(3)->create(['user_id' => $teacher->id]);
        $otherTeacherPatients = Patient::factory()->count(2)->create(['user_id' => $otherTeacher->id]);

        $response = $this->actingAs($student)->get(route('patient.index'));

        $response->assertOk();

        // Should see their teacher's patients
        foreach ($teacherPatients as $patient) {
            $response->assertSee($patient->name);
        }

        // Should NOT see other teacher's patients
        foreach ($otherTeacherPatients as $patient) {
            $response->assertDontSee($patient->name);
        }
    });

    test('admin sees all patients', function () {
        $admin = User::factory()->admin()->create();
        $teacher1 = User::factory()->teacher()->create();
        $teacher2 = User::factory()->teacher()->create();

        $allPatients = collect([
            Patient::factory()->create(['user_id' => $teacher1->id]),
            Patient::factory()->create(['user_id' => $teacher2->id]),
        ]);

        $response = $this->actingAs($admin)->get(route('patient.index'));

        $response->assertOk();

        // Admin should see all patients
        foreach ($allPatients as $patient) {
            $response->assertSee($patient->name);
        }
    });
});

describe('Patient import authorization', function () {
    test('teacher can import patients', function () {
        $teacher = User::factory()->teacher()->create();

        $csvContent = "name,gender,birth_date,mrn,room,admission_date,attending_md,diagnosis,diet_order,activity_level,procedure,status,isolation,unit\n";
        $csvContent .= 'John Doe,Male,1990-01-15,MRN001,101,2025-11-01,Dr. Smith,Diabetes,Regular,Ambulatory,Surgery,Active,None,ICU';

        $file = Illuminate\Http\UploadedFile::fake()->createWithContent('patients.csv', $csvContent);

        Livewire\Livewire::actingAs($teacher)
            ->test(App\Livewire\Patient\Import::class)
            ->set('csvFile', $file)
            ->call('import')
            ->assertHasNoErrors();

        expect(Patient::where('user_id', $teacher->id)->exists())->toBeTrue();
    });

    test('student cannot import patients', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create();

        Livewire\Livewire::actingAs($student)
            ->test(App\Livewire\Patient\Import::class)
            ->assertForbidden();

        expect(Patient::where('user_id', $student->id)->exists())->toBeFalse();
    });

    test('admin can import patients', function () {
        $admin = User::factory()->admin()->create();

        $csvContent = "name,gender,birth_date,mrn,room,admission_date,attending_md,diagnosis,diet_order,activity_level,procedure,status,isolation,unit\n";
        $csvContent .= 'John Doe,Male,1990-01-15,MRN001,101,2025-11-01,Dr. Smith,Diabetes,Regular,Ambulatory,Surgery,Active,None,ICU';

        $file = Illuminate\Http\UploadedFile::fake()->createWithContent('patients.csv', $csvContent);

        Livewire\Livewire::actingAs($admin)
            ->test(App\Livewire\Patient\Import::class)
            ->set('csvFile', $file)
            ->call('import')
            ->assertHasNoErrors();

        expect(Patient::where('user_id', $admin->id)->exists())->toBeTrue();
    });
});

describe('Student invitation authorization', function () {
    test('teacher can invite students', function () {
        Illuminate\Support\Facades\Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire\Livewire::actingAs($teacher)
            ->test(App\Livewire\Student\Invite::class)
            ->set('form.email', 'newstudent@example.com')
            ->call('sendInvite')
            ->assertHasNoErrors();

        expect(App\Models\Invitation::where('email', 'newstudent@example.com')->exists())->toBeTrue();
    });

    test('student cannot invite students', function () {
        Illuminate\Support\Facades\Mail::fake();

        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create();

        Livewire\Livewire::actingAs($student)
            ->test(App\Livewire\Student\Invite::class)
            ->set('form.email', 'another@example.com')
            ->call('sendInvite')
            ->assertForbidden();

        expect(App\Models\Invitation::where('email', 'another@example.com')->exists())->toBeFalse();
    });
});
