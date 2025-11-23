<?php

declare(strict_types=1);

use App\Enums\BpMethod;
use App\Enums\BpSource;
use App\Enums\Heart;
use App\Enums\Oxygen;
use App\Enums\PainScale;
use App\Enums\PatientPosition;
use App\Enums\Temp;
use App\Models\Patient;
use App\Models\User;
use App\Models\Vital;

describe('Vital model', function () {
    test('can be created using factory', function () {
        $vital = Vital::factory()->create();

        expect($vital)->toBeInstanceOf(Vital::class)
            ->and($vital->exists)->toBeTrue();
    });

    test('belongs to a patient', function () {
        $patient = Patient::factory()->create();
        $vital = Vital::factory()->for($patient)->create();

        expect($vital->patient)->toBeInstanceOf(Patient::class)
            ->and($vital->patient->id)->toBe($patient->id);
    });

    test('belongs to a user', function () {
        $user = User::factory()->create();
        $vital = Vital::factory()->create(['user_id' => $user->id]);

        expect($vital->user)->toBeInstanceOf(User::class)
            ->and($vital->user->id)->toBe($user->id);
    });

    test('can have a deleted by user', function () {
        $user = User::factory()->create();
        $vital = Vital::factory()->create(['deleted_by' => $user->id]);

        expect($vital->deletedBy)->toBeInstanceOf(User::class)
            ->and($vital->deletedBy->id)->toBe($user->id);
    });

    test('uses soft deletes', function () {
        $vital = Vital::factory()->create();
        $vitalId = $vital->id;

        $vital->delete();

        expect(Vital::find($vitalId))->toBeNull()
            ->and(Vital::withTrashed()->find($vitalId))->not->toBeNull();
    });
});

describe('Vital model casts', function () {
    test('casts temperature_source to Temp enum', function () {
        $vital = Vital::factory()->create(['temperature_source' => 'oral']);

        expect($vital->temperature_source)->toBeInstanceOf(Temp::class)
            ->and($vital->temperature_source)->toBe(Temp::ORAL);
    });

    test('casts heart_rate_source to Heart enum', function () {
        $vital = Vital::factory()->create(['heart_rate_source' => 'apical']);

        expect($vital->heart_rate_source)->toBeInstanceOf(Heart::class)
            ->and($vital->heart_rate_source)->toBe(Heart::APICAL);
    });

    test('casts bp_source to BpSource enum', function () {
        $vital = Vital::factory()->create(['bp_source' => 'leftarm']);

        expect($vital->bp_source)->toBeInstanceOf(BpSource::class)
            ->and($vital->bp_source)->toBe(BpSource::LEFTARM);
    });

    test('casts bp_method to BpMethod enum', function () {
        $vital = Vital::factory()->create(['bp_method' => 'manual']);

        expect($vital->bp_method)->toBeInstanceOf(BpMethod::class)
            ->and($vital->bp_method)->toBe(BpMethod::MANUAL);
    });

    test('casts patient_position to PatientPosition enum', function () {
        $vital = Vital::factory()->create(['patient_position' => 'sitting']);

        expect($vital->patient_position)->toBeInstanceOf(PatientPosition::class)
            ->and($vital->patient_position)->toBe(PatientPosition::SITTING);
    });

    test('casts pain_scale to PainScale enum', function () {
        $vital = Vital::factory()->create(['pain_scale' => 'numeric']);

        expect($vital->pain_scale)->toBeInstanceOf(PainScale::class)
            ->and($vital->pain_scale)->toBe(PainScale::NUMERIC);
    });

    test('casts oxygen_device to Oxygen enum', function () {
        $vital = Vital::factory()->create(['oxygen_device' => 'mask']);

        expect($vital->oxygen_device)->toBeInstanceOf(Oxygen::class)
            ->and($vital->oxygen_device)->toBe(Oxygen::MASK);
    });

    test('casts pain_descriptors to array', function () {
        $descriptors = ['sharp', 'burning'];
        $vital = Vital::factory()->create(['pain_descriptors' => $descriptors]);

        expect($vital->pain_descriptors)->toBeArray()
            ->and($vital->pain_descriptors)->toBe($descriptors);
    });

    test('casts integer fields correctly', function () {
        $vital = Vital::factory()->create([
            'temperature' => 986,
            'heart_rate' => 72,
            'resp' => 16,
            'systolic' => 120,
            'diastolic' => 80,
            'pain_goal' => 3,
            'sp02' => 98,
        ]);

        expect($vital->temperature)->toBeInt()
            ->and($vital->heart_rate)->toBeInt()
            ->and($vital->resp)->toBeInt()
            ->and($vital->systolic)->toBeInt()
            ->and($vital->diastolic)->toBeInt()
            ->and($vital->pain_goal)->toBeInt()
            ->and($vital->sp02)->toBeInt();
    });
});

describe('Vital patient relationship', function () {
    test('patient can have multiple vitals', function () {
        $patient = Patient::factory()->create();
        $vitals = Vital::factory()->count(3)->for($patient)->create();

        expect($patient->vitals)->toHaveCount(3)
            ->and($patient->vitals->first())->toBeInstanceOf(Vital::class);
    });

    test('deleting patient cascades to vitals', function () {
        $patient = Patient::factory()->create();
        $vital = Vital::factory()->for($patient)->create();
        $vitalId = $vital->id;

        $patient->delete();

        expect(Vital::withTrashed()->find($vitalId))->toBeNull();
    });
});
