<?php

declare(strict_types=1);

use App\Livewire\Patient\Create;
use App\Models\Patient;
use App\Models\User;
use Livewire\Livewire;

describe('Patient\Create component rendering', function () {
    test('component renders successfully for authenticated user', function () {
        $user = User::factory()->teacher()->create();

        Livewire::actingAs($user)
            ->test(Create::class)
            ->assertStatus(200);
    });

    test('guests cannot access create component', function () {
        Livewire::test(Create::class)
            ->assertForbidden();
    });
});

describe('Patient\Create component patient creation', function () {
    test('component can create patient with valid data', function () {
        $user = User::factory()->teacher()->create();

        $patientData = [
            'form.name' => 'John Doe',
            'form.gender' => 'Male',
            'form.birth_date' => '1990-01-15',
            'form.mrn' => 'MRN001',
            'form.room' => '101',
            'form.admission_date' => '2024-11-01',
            'form.attending_md' => 'Dr. Smith',
            'form.diagnosis' => 'Diabetes',
            'form.diet_order' => 'Regular',
            'form.activity_level' => 'Ambulatory',
            'form.procedure' => 'Surgery',
            'form.status' => 'Active',
            'form.isolation' => 'None',
            'form.unit' => 'ICU',
        ];

        Livewire::actingAs($user)
            ->test(Create::class)
            ->set('form.name', $patientData['form.name'])
            ->set('form.gender', $patientData['form.gender'])
            ->set('form.birth_date', $patientData['form.birth_date'])
            ->set('form.mrn', $patientData['form.mrn'])
            ->set('form.room', $patientData['form.room'])
            ->set('form.admission_date', $patientData['form.admission_date'])
            ->set('form.attending_md', $patientData['form.attending_md'])
            ->set('form.diagnosis', $patientData['form.diagnosis'])
            ->set('form.diet_order', $patientData['form.diet_order'])
            ->set('form.activity_level', $patientData['form.activity_level'])
            ->set('form.procedure', $patientData['form.procedure'])
            ->set('form.status', $patientData['form.status'])
            ->set('form.isolation', $patientData['form.isolation'])
            ->set('form.unit', $patientData['form.unit'])
            ->call('create')
            ->assertHasNoErrors();

        expect(Patient::where('user_id', $user->id)->count())->toBe(1)
            ->and(Patient::where('name', 'John Doe')->exists())->toBeTrue();
    });

    test('created patient has correct attributes', function () {
        $user = User::factory()->teacher()->create();

        Livewire::actingAs($user)
            ->test(Create::class)
            ->set('form.name', 'Jane Smith')
            ->set('form.gender', 'Female')
            ->set('form.birth_date', '1985-05-20')
            ->set('form.mrn', 'MRN002')
            ->set('form.room', '102')
            ->set('form.admission_date', '2024-11-02')
            ->set('form.attending_md', 'Dr. Jones')
            ->set('form.diagnosis', 'Hypertension')
            ->set('form.diet_order', 'Low Sodium')
            ->set('form.activity_level', 'Bedrest')
            ->set('form.procedure', 'Physical Therapy')
            ->set('form.status', 'Stable')
            ->set('form.isolation', 'Contact')
            ->set('form.unit', 'General')
            ->call('create');

        $patient = Patient::where('name', 'Jane Smith')->first();

        expect($patient)->not->toBeNull()
            ->and($patient->user_id)->toBe($user->id)
            ->and($patient->gender)->toBe('Female')
            ->and($patient->mrn)->toBe('MRN002')
            ->and($patient->room)->toBe('102')
            ->and($patient->attending_md)->toBe('Dr. Jones')
            ->and($patient->diagnosis)->toBe('Hypertension')
            ->and($patient->diet_order)->toBe('Low Sodium')
            ->and($patient->activity_level)->toBe('Bedrest')
            ->and($patient->procedure)->toBe('Physical Therapy')
            ->and($patient->status)->toBe('Stable')
            ->and($patient->isolation)->toBe('Contact')
            ->and($patient->unit)->toBe('General');
    });

    test('component resets form after successful creation', function () {
        $user = User::factory()->teacher()->create();

        Livewire::actingAs($user)
            ->test(Create::class)
            ->set('form.name', 'John Doe')
            ->set('form.gender', 'Male')
            ->set('form.birth_date', '1990-01-15')
            ->set('form.mrn', 'MRN001')
            ->set('form.room', '101')
            ->set('form.admission_date', '2024-11-01')
            ->set('form.attending_md', 'Dr. Smith')
            ->set('form.diagnosis', 'Diabetes')
            ->set('form.diet_order', 'Regular')
            ->set('form.activity_level', 'Ambulatory')
            ->set('form.procedure', 'Surgery')
            ->set('form.status', 'Active')
            ->set('form.isolation', 'None')
            ->set('form.unit', 'ICU')
            ->call('create')
            ->assertSet('form.name', '')
            ->assertSet('form.gender', '')
            ->assertSet('form.mrn', '');
    });

    test('created patients belong to authenticated user', function () {
        $user1 = User::factory()->teacher()->create();
        $user2 = User::factory()->teacher()->create();

        Livewire::actingAs($user1)
            ->test(Create::class)
            ->set('form.name', 'John Doe')
            ->set('form.gender', 'Male')
            ->set('form.birth_date', '1990-01-15')
            ->set('form.mrn', 'MRN001')
            ->set('form.room', '101')
            ->set('form.admission_date', '2024-11-01')
            ->set('form.attending_md', 'Dr. Smith')
            ->set('form.diagnosis', 'Diabetes')
            ->set('form.diet_order', 'Regular')
            ->set('form.activity_level', 'Ambulatory')
            ->set('form.procedure', 'Surgery')
            ->set('form.status', 'Active')
            ->set('form.isolation', 'None')
            ->set('form.unit', 'ICU')
            ->call('create');

        expect(Patient::where('user_id', $user1->id)->count())->toBe(1)
            ->and(Patient::where('user_id', $user2->id)->count())->toBe(0);
    });
});

describe('Patient\Create component validation', function () {
    test('name is required', function () {
        $user = User::factory()->teacher()->create();

        Livewire::actingAs($user)
            ->test(Create::class)
            ->set('form.name', '')
            ->set('form.gender', 'Male')
            ->set('form.birth_date', '1990-01-15')
            ->set('form.mrn', 'MRN001')
            ->set('form.room', '101')
            ->set('form.admission_date', '2024-11-01')
            ->set('form.attending_md', 'Dr. Smith')
            ->set('form.diagnosis', 'Diabetes')
            ->set('form.diet_order', 'Regular')
            ->set('form.activity_level', 'Ambulatory')
            ->set('form.procedure', 'Surgery')
            ->set('form.status', 'Active')
            ->set('form.isolation', 'None')
            ->set('form.unit', 'ICU')
            ->call('create')
            ->assertHasErrors(['form.name' => 'required']);

        expect(Patient::count())->toBe(0);
    });

    test('gender is required', function () {
        $user = User::factory()->teacher()->create();

        Livewire::actingAs($user)
            ->test(Create::class)
            ->set('form.name', 'John Doe')
            ->set('form.gender', '')
            ->set('form.birth_date', '1990-01-15')
            ->set('form.mrn', 'MRN001')
            ->set('form.room', '101')
            ->set('form.admission_date', '2024-11-01')
            ->set('form.attending_md', 'Dr. Smith')
            ->set('form.diagnosis', 'Diabetes')
            ->set('form.diet_order', 'Regular')
            ->set('form.activity_level', 'Ambulatory')
            ->set('form.procedure', 'Surgery')
            ->set('form.status', 'Active')
            ->set('form.isolation', 'None')
            ->set('form.unit', 'ICU')
            ->call('create')
            ->assertHasErrors(['form.gender' => 'required']);

        expect(Patient::count())->toBe(0);
    });

    test('birth_date is required', function () {
        $user = User::factory()->teacher()->create();

        Livewire::actingAs($user)
            ->test(Create::class)
            ->set('form.name', 'John Doe')
            ->set('form.gender', 'Male')
            ->set('form.birth_date', '')
            ->set('form.mrn', 'MRN001')
            ->set('form.room', '101')
            ->set('form.admission_date', '2024-11-01')
            ->set('form.attending_md', 'Dr. Smith')
            ->set('form.diagnosis', 'Diabetes')
            ->set('form.diet_order', 'Regular')
            ->set('form.activity_level', 'Ambulatory')
            ->set('form.procedure', 'Surgery')
            ->set('form.status', 'Active')
            ->set('form.isolation', 'None')
            ->set('form.unit', 'ICU')
            ->call('create')
            ->assertHasErrors(['form.birth_date' => 'required']);

        expect(Patient::count())->toBe(0);
    });

    test('birth_date must be a valid date', function () {
        $user = User::factory()->teacher()->create();

        Livewire::actingAs($user)
            ->test(Create::class)
            ->set('form.name', 'John Doe')
            ->set('form.gender', 'Male')
            ->set('form.birth_date', 'invalid-date')
            ->set('form.mrn', 'MRN001')
            ->set('form.room', '101')
            ->set('form.admission_date', '2024-11-01')
            ->set('form.attending_md', 'Dr. Smith')
            ->set('form.diagnosis', 'Diabetes')
            ->set('form.diet_order', 'Regular')
            ->set('form.activity_level', 'Ambulatory')
            ->set('form.procedure', 'Surgery')
            ->set('form.status', 'Active')
            ->set('form.isolation', 'None')
            ->set('form.unit', 'ICU')
            ->call('create')
            ->assertHasErrors(['form.birth_date' => 'date']);

        expect(Patient::count())->toBe(0);
    });

    test('birth_date must be before today', function () {
        $user = User::factory()->teacher()->create();

        Livewire::actingAs($user)
            ->test(Create::class)
            ->set('form.name', 'John Doe')
            ->set('form.gender', 'Male')
            ->set('form.birth_date', now()->addDay()->format('Y-m-d'))
            ->set('form.mrn', 'MRN001')
            ->set('form.room', '101')
            ->set('form.admission_date', '2024-11-01')
            ->set('form.attending_md', 'Dr. Smith')
            ->set('form.diagnosis', 'Diabetes')
            ->set('form.diet_order', 'Regular')
            ->set('form.activity_level', 'Ambulatory')
            ->set('form.procedure', 'Surgery')
            ->set('form.status', 'Active')
            ->set('form.isolation', 'None')
            ->set('form.unit', 'ICU')
            ->call('create')
            ->assertHasErrors(['form.birth_date' => 'before']);

        expect(Patient::count())->toBe(0);
    });

    test('mrn is required', function () {
        $user = User::factory()->teacher()->create();

        Livewire::actingAs($user)
            ->test(Create::class)
            ->set('form.name', 'John Doe')
            ->set('form.gender', 'Male')
            ->set('form.birth_date', '1990-01-15')
            ->set('form.mrn', '')
            ->set('form.room', '101')
            ->set('form.admission_date', '2024-11-01')
            ->set('form.attending_md', 'Dr. Smith')
            ->set('form.diagnosis', 'Diabetes')
            ->set('form.diet_order', 'Regular')
            ->set('form.activity_level', 'Ambulatory')
            ->set('form.procedure', 'Surgery')
            ->set('form.status', 'Active')
            ->set('form.isolation', 'None')
            ->set('form.unit', 'ICU')
            ->call('create')
            ->assertHasErrors(['form.mrn' => 'required']);

        expect(Patient::count())->toBe(0);
    });

    test('admission_date is required', function () {
        $user = User::factory()->teacher()->create();

        Livewire::actingAs($user)
            ->test(Create::class)
            ->set('form.name', 'John Doe')
            ->set('form.gender', 'Male')
            ->set('form.birth_date', '1990-01-15')
            ->set('form.mrn', 'MRN001')
            ->set('form.room', '101')
            ->set('form.admission_date', '')
            ->set('form.attending_md', 'Dr. Smith')
            ->set('form.diagnosis', 'Diabetes')
            ->set('form.diet_order', 'Regular')
            ->set('form.activity_level', 'Ambulatory')
            ->set('form.procedure', 'Surgery')
            ->set('form.status', 'Active')
            ->set('form.isolation', 'None')
            ->set('form.unit', 'ICU')
            ->call('create')
            ->assertHasErrors(['form.admission_date' => 'required']);

        expect(Patient::count())->toBe(0);
    });

    test('admission_date must be a valid date', function () {
        $user = User::factory()->teacher()->create();

        Livewire::actingAs($user)
            ->test(Create::class)
            ->set('form.name', 'John Doe')
            ->set('form.gender', 'Male')
            ->set('form.birth_date', '1990-01-15')
            ->set('form.mrn', 'MRN001')
            ->set('form.room', '101')
            ->set('form.admission_date', 'invalid-date')
            ->set('form.attending_md', 'Dr. Smith')
            ->set('form.diagnosis', 'Diabetes')
            ->set('form.diet_order', 'Regular')
            ->set('form.activity_level', 'Ambulatory')
            ->set('form.procedure', 'Surgery')
            ->set('form.status', 'Active')
            ->set('form.isolation', 'None')
            ->set('form.unit', 'ICU')
            ->call('create')
            ->assertHasErrors(['form.admission_date' => 'date']);

        expect(Patient::count())->toBe(0);
    });

    test('admission_date must be before today', function () {
        $user = User::factory()->teacher()->create();

        Livewire::actingAs($user)
            ->test(Create::class)
            ->set('form.name', 'John Doe')
            ->set('form.gender', 'Male')
            ->set('form.birth_date', '1990-01-15')
            ->set('form.mrn', 'MRN001')
            ->set('form.room', '101')
            ->set('form.admission_date', now()->addDay()->format('Y-m-d'))
            ->set('form.attending_md', 'Dr. Smith')
            ->set('form.diagnosis', 'Diabetes')
            ->set('form.diet_order', 'Regular')
            ->set('form.activity_level', 'Ambulatory')
            ->set('form.procedure', 'Surgery')
            ->set('form.status', 'Active')
            ->set('form.isolation', 'None')
            ->set('form.unit', 'ICU')
            ->call('create')
            ->assertHasErrors(['form.admission_date' => 'before']);

        expect(Patient::count())->toBe(0);
    });

    test('all required fields must be present', function (string $field) {
        $user = User::factory()->teacher()->create();

        $data = [
            'form.name' => 'John Doe',
            'form.gender' => 'Male',
            'form.birth_date' => '1990-01-15',
            'form.mrn' => 'MRN001',
            'form.room' => '101',
            'form.admission_date' => '2024-11-01',
            'form.attending_md' => 'Dr. Smith',
            'form.diagnosis' => 'Diabetes',
            'form.diet_order' => 'Regular',
            'form.activity_level' => 'Ambulatory',
            'form.procedure' => 'Surgery',
            'form.status' => 'Active',
            'form.isolation' => 'None',
            'form.unit' => 'ICU',
        ];

        $data[$field] = '';

        $test = Livewire::actingAs($user)->test(Create::class);

        foreach ($data as $key => $value) {
            $test->set($key, $value);
        }

        $test->call('create')
            ->assertHasErrors([$field => 'required']);

        expect(Patient::count())->toBe(0);
    })->with([
        'form.name',
        'form.gender',
        'form.birth_date',
        'form.mrn',
        'form.room',
        'form.admission_date',
        'form.attending_md',
        'form.diagnosis',
        'form.diet_order',
        'form.activity_level',
        'form.procedure',
        'form.status',
        'form.isolation',
        'form.unit',
    ]);

    test('string fields cannot exceed 255 characters', function (string $field) {
        $user = User::factory()->teacher()->create();

        $longString = str_repeat('a', 256);

        $data = [
            'form.name' => 'John Doe',
            'form.gender' => 'Male',
            'form.birth_date' => '1990-01-15',
            'form.mrn' => 'MRN001',
            'form.room' => '101',
            'form.admission_date' => '2024-11-01',
            'form.attending_md' => 'Dr. Smith',
            'form.diagnosis' => 'Diabetes',
            'form.diet_order' => 'Regular',
            'form.activity_level' => 'Ambulatory',
            'form.procedure' => 'Surgery',
            'form.status' => 'Active',
            'form.isolation' => 'None',
            'form.unit' => 'ICU',
        ];

        $data[$field] = $longString;

        $test = Livewire::actingAs($user)->test(Create::class);

        foreach ($data as $key => $value) {
            $test->set($key, $value);
        }

        $test->call('create')
            ->assertHasErrors([$field => 'max']);

        expect(Patient::count())->toBe(0);
    })->with([
        'form.name',
        'form.gender',
        'form.mrn',
        'form.room',
        'form.attending_md',
        'form.diagnosis',
        'form.diet_order',
        'form.activity_level',
        'form.procedure',
        'form.status',
        'form.isolation',
        'form.unit',
    ]);
});
