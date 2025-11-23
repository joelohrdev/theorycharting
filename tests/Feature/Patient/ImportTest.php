<?php

declare(strict_types=1);

use App\Livewire\Patient\Import;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;

describe('Patient\Import component rendering', function () {
    test('component renders successfully for authenticated user', function () {
        $user = User::factory()->teacher()->create();

        Livewire::actingAs($user)
            ->test(Import::class)
            ->assertStatus(200);
    });

    test('component displays import button', function () {
        $user = User::factory()->teacher()->create();

        Livewire::actingAs($user)
            ->test(Import::class)
            ->assertSee('Import Patients');
    });

    test('component displays required CSV columns information', function () {
        $user = User::factory()->teacher()->create();

        Livewire::actingAs($user)
            ->test(Import::class)
            ->assertSee('Required CSV columns')
            ->assertSee('name')
            ->assertSee('mrn');
    });
});

describe('Patient\Import component CSV import', function () {
    test('component can import valid CSV file', function () {
        $user = User::factory()->teacher()->create();

        $csvContent = "name,gender,birth_date,mrn,room,admission_date,attending_md,diagnosis,diet_order,activity_level,procedure,status,isolation,unit\n";
        $csvContent .= "John Doe,Male,1990-01-15,MRN001,101,2025-11-01,Dr. Smith,Diabetes,Regular,Ambulatory,Surgery,Active,None,ICU\n";
        $csvContent .= 'Jane Smith,Female,1985-05-20,MRN002,102,2025-11-02,Dr. Jones,Hypertension,Low Sodium,Bedrest,Physical Therapy,Stable,Contact,General';

        $file = UploadedFile::fake()->createWithContent('patients.csv', $csvContent);

        Livewire::actingAs($user)
            ->test(Import::class)
            ->set('csvFile', $file)
            ->call('import')
            ->assertHasNoErrors()
            ->assertDispatched('patients-imported');

        expect(Patient::where('user_id', $user->id)->count())->toBe(2)
            ->and(Patient::where('name', 'John Doe')->exists())->toBeTrue()
            ->and(Patient::where('name', 'Jane Smith')->exists())->toBeTrue();
    });

    test('imported patient has correct attributes', function () {
        $user = User::factory()->teacher()->create();

        $csvContent = "name,gender,birth_date,mrn,room,admission_date,attending_md,diagnosis,diet_order,activity_level,procedure,status,isolation,unit\n";
        $csvContent .= 'John Doe,Male,1990-01-15,MRN001,101,2025-11-01,Dr. Smith,Diabetes,Regular,Ambulatory,Surgery,Active,None,ICU';

        $file = UploadedFile::fake()->createWithContent('patients.csv', $csvContent);

        Livewire::actingAs($user)
            ->test(Import::class)
            ->set('csvFile', $file)
            ->call('import');

        $patient = Patient::where('name', 'John Doe')->first();

        expect($patient)->not->toBeNull()
            ->and($patient->user_id)->toBe($user->id)
            ->and($patient->gender)->toBe('Male')
            ->and($patient->mrn)->toBe('MRN001')
            ->and($patient->room)->toBe('101')
            ->and($patient->attending_md)->toBe('Dr. Smith')
            ->and($patient->diagnosis)->toBe('Diabetes')
            ->and($patient->status)->toBe('Active')
            ->and($patient->unit)->toBe('ICU')
            ->and($patient->uuid)->not->toBeNull();
    });

    test('component dispatches close-modal event after successful import', function () {
        $user = User::factory()->teacher()->create();

        $csvContent = "name,gender,birth_date,mrn,room,admission_date,attending_md,diagnosis,diet_order,activity_level,procedure,status,isolation,unit\n";
        $csvContent .= 'John Doe,Male,1990-01-15,MRN001,101,2025-11-01,Dr. Smith,Diabetes,Regular,Ambulatory,Surgery,Active,None,ICU';

        $file = UploadedFile::fake()->createWithContent('patients.csv', $csvContent);

        Livewire::actingAs($user)
            ->test(Import::class)
            ->set('csvFile', $file)
            ->call('import')
            ->assertDispatched('close-modal');
    });

    test('component resets file after successful import', function () {
        $user = User::factory()->teacher()->create();

        $csvContent = "name,gender,birth_date,mrn,room,admission_date,attending_md,diagnosis,diet_order,activity_level,procedure,status,isolation,unit\n";
        $csvContent .= 'John Doe,Male,1990-01-15,MRN001,101,2025-11-01,Dr. Smith,Diabetes,Regular,Ambulatory,Surgery,Active,None,ICU';

        $file = UploadedFile::fake()->createWithContent('patients.csv', $csvContent);

        Livewire::actingAs($user)
            ->test(Import::class)
            ->set('csvFile', $file)
            ->call('import')
            ->assertSet('csvFile', null);
    });
});

describe('Patient\Import component validation', function () {
    test('component requires CSV file', function () {
        $user = User::factory()->teacher()->create();

        Livewire::actingAs($user)
            ->test(Import::class)
            ->set('csvFile', null)
            ->call('import')
            ->assertHasErrors(['csvFile' => 'required']);

        expect(Patient::count())->toBe(0);
    });

    test('component requires valid file type', function () {
        $user = User::factory()->teacher()->create();

        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        Livewire::actingAs($user)
            ->test(Import::class)
            ->set('csvFile', $file)
            ->call('import')
            ->assertHasErrors(['csvFile' => 'mimes']);

        expect(Patient::count())->toBe(0);
    });

    test('component validates required CSV fields', function () {
        $user = User::factory()->teacher()->create();

        $csvContent = "name,gender,birth_date\n";
        $csvContent .= 'John Doe,Male,1990-01-15';

        $file = UploadedFile::fake()->createWithContent('patients.csv', $csvContent);

        Livewire::actingAs($user)
            ->test(Import::class)
            ->set('csvFile', $file)
            ->call('import');

        expect(Patient::count())->toBe(0);
    });

    test('component validates date format in birth_date', function () {
        $user = User::factory()->teacher()->create();

        $csvContent = "name,gender,birth_date,mrn,room,admission_date,attending_md,diagnosis,diet_order,activity_level,procedure,status,isolation,unit\n";
        $csvContent .= 'John Doe,Male,invalid-date,MRN001,101,2025-11-01,Dr. Smith,Diabetes,Regular,Ambulatory,Surgery,Active,None,ICU';

        $file = UploadedFile::fake()->createWithContent('patients.csv', $csvContent);

        Livewire::actingAs($user)
            ->test(Import::class)
            ->set('csvFile', $file)
            ->call('import');

        expect(Patient::count())->toBe(0);
    });

    test('component shows error message on validation failure', function () {
        $user = User::factory()->teacher()->create();

        $csvContent = "name,gender,birth_date,mrn,room,admission_date,attending_md,diagnosis,diet_order,activity_level,procedure,status,isolation,unit\n";
        $csvContent .= ',Male,1990-01-15,MRN001,101,2025-11-01,Dr. Smith,Diabetes,Regular,Ambulatory,Surgery,Active,None,ICU';

        $file = UploadedFile::fake()->createWithContent('patients.csv', $csvContent);

        Livewire::actingAs($user)
            ->test(Import::class)
            ->set('csvFile', $file)
            ->call('import')
            ->assertSet('importing', false);
    });
});

describe('Patient\Import component authorization', function () {
    test('guests cannot access import component', function () {
        Livewire::test(Import::class)
            ->assertForbidden();
    });

    test('authenticated user can import patients', function () {
        $user = User::factory()->teacher()->create();

        $csvContent = "name,gender,birth_date,mrn,room,admission_date,attending_md,diagnosis,diet_order,activity_level,procedure,status,isolation,unit\n";
        $csvContent .= 'John Doe,Male,1990-01-15,MRN001,101,2025-11-01,Dr. Smith,Diabetes,Regular,Ambulatory,Surgery,Active,None,ICU';

        $file = UploadedFile::fake()->createWithContent('patients.csv', $csvContent);

        Livewire::actingAs($user)
            ->test(Import::class)
            ->set('csvFile', $file)
            ->call('import')
            ->assertHasNoErrors();

        expect(Patient::where('user_id', $user->id)->exists())->toBeTrue();
    });

    test('imported patients belong to authenticated user', function () {
        $user1 = User::factory()->teacher()->create();
        $user2 = User::factory()->teacher()->create();

        $csvContent = "name,gender,birth_date,mrn,room,admission_date,attending_md,diagnosis,diet_order,activity_level,procedure,status,isolation,unit\n";
        $csvContent .= 'John Doe,Male,1990-01-15,MRN001,101,2025-11-01,Dr. Smith,Diabetes,Regular,Ambulatory,Surgery,Active,None,ICU';

        $file = UploadedFile::fake()->createWithContent('patients.csv', $csvContent);

        Livewire::actingAs($user1)
            ->test(Import::class)
            ->set('csvFile', $file)
            ->call('import');

        expect(Patient::where('user_id', $user1->id)->count())->toBe(1)
            ->and(Patient::where('user_id', $user2->id)->count())->toBe(0);
    });
});
