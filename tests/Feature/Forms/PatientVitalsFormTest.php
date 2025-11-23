<?php

declare(strict_types=1);

use App\Enums\BpMethod;
use App\Enums\BpSource;
use App\Enums\Heart;
use App\Enums\Oxygen;
use App\Enums\PainScale;
use App\Enums\PatientPosition;
use App\Enums\Temp;
use App\Livewire\Patient\VitalsForm;
use App\Models\Patient;
use App\Models\User;
use App\Models\Vital;
use Livewire\Livewire;

describe('PatientVitalsForm validation', function () {
    describe('temperature validation', function () {
        test('temperature must be at least 50', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(VitalsForm::class, ['patient' => $patient])
                ->set('form.temperature', 49)
                ->call('save')
                ->assertHasErrors(['form.temperature' => 'min']);
        });

        test('temperature must be at most 115', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(VitalsForm::class, ['patient' => $patient])
                ->set('form.temperature', 116)
                ->call('save')
                ->assertHasErrors(['form.temperature' => 'max']);
        });

        test('temperature accepts valid value', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(VitalsForm::class, ['patient' => $patient])
                ->set('form.temperature', 98.6)
                ->call('save')
                ->assertHasNoErrors(['form.temperature']);
        });

        test('temperature source must be valid enum value', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(VitalsForm::class, ['patient' => $patient])
                ->set('form.temperatureSource', 'invalid')
                ->call('save')
                ->assertHasErrors(['form.temperatureSource']);
        });

        test('temperature source accepts valid enum value', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(VitalsForm::class, ['patient' => $patient])
                ->set('form.temperatureSource', 'oral')
                ->call('save')
                ->assertHasNoErrors(['form.temperatureSource']);
        });
    });

    describe('heart rate validation', function () {
        test('heart rate must be at least 1', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(VitalsForm::class, ['patient' => $patient])
                ->set('form.heartRate', 0)
                ->call('save')
                ->assertHasErrors(['form.heartRate' => 'min']);
        });

        test('heart rate must be at most 300', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(VitalsForm::class, ['patient' => $patient])
                ->set('form.heartRate', 301)
                ->call('save')
                ->assertHasErrors(['form.heartRate' => 'max']);
        });

        test('heart rate accepts valid value', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(VitalsForm::class, ['patient' => $patient])
                ->set('form.heartRate', 72)
                ->call('save')
                ->assertHasNoErrors(['form.heartRate']);
        });

        test('heart rate source must be valid enum value', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(VitalsForm::class, ['patient' => $patient])
                ->set('form.heartRateSource', 'invalid')
                ->call('save')
                ->assertHasErrors(['form.heartRateSource']);
        });
    });

    describe('respiration validation', function () {
        test('resp must be at least 1', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(VitalsForm::class, ['patient' => $patient])
                ->set('form.resp', 0)
                ->call('save')
                ->assertHasErrors(['form.resp' => 'min']);
        });

        test('resp must be at most 100', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(VitalsForm::class, ['patient' => $patient])
                ->set('form.resp', 101)
                ->call('save')
                ->assertHasErrors(['form.resp' => 'max']);
        });
    });

    describe('blood pressure validation', function () {
        test('systolic must be at least 1', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(VitalsForm::class, ['patient' => $patient])
                ->set('form.systolic', 0)
                ->call('save')
                ->assertHasErrors(['form.systolic' => 'min']);
        });

        test('systolic must be at most 300', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(VitalsForm::class, ['patient' => $patient])
                ->set('form.systolic', 301)
                ->call('save')
                ->assertHasErrors(['form.systolic' => 'max']);
        });

        test('diastolic must be at least 1', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(VitalsForm::class, ['patient' => $patient])
                ->set('form.diastolic', 0)
                ->call('save')
                ->assertHasErrors(['form.diastolic' => 'min']);
        });

        test('diastolic must be at most 200', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(VitalsForm::class, ['patient' => $patient])
                ->set('form.diastolic', 201)
                ->call('save')
                ->assertHasErrors(['form.diastolic' => 'max']);
        });

        test('bp source must be valid enum value', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(VitalsForm::class, ['patient' => $patient])
                ->set('form.bpSource', 'invalid')
                ->call('save')
                ->assertHasErrors(['form.bpSource']);
        });

        test('bp method must be valid enum value', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(VitalsForm::class, ['patient' => $patient])
                ->set('form.bpMethod', 'invalid')
                ->call('save')
                ->assertHasErrors(['form.bpMethod']);
        });

        test('patient position must be valid enum value', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(VitalsForm::class, ['patient' => $patient])
                ->set('form.patientPosition', 'invalid')
                ->call('save')
                ->assertHasErrors(['form.patientPosition']);
        });
    });

    describe('pain validation', function () {
        test('pain scale must be valid enum value', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(VitalsForm::class, ['patient' => $patient])
                ->set('form.painScale', 'invalid')
                ->call('save')
                ->assertHasErrors(['form.painScale']);
        });

        test('pain goal must be at least 0', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(VitalsForm::class, ['patient' => $patient])
                ->set('form.painGoal', -1)
                ->call('save')
                ->assertHasErrors(['form.painGoal' => 'min']);
        });

        test('pain goal must be at most 10', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(VitalsForm::class, ['patient' => $patient])
                ->set('form.painGoal', 11)
                ->call('save')
                ->assertHasErrors(['form.painGoal' => 'max']);
        });

        test('pain descriptors must contain valid enum values', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(VitalsForm::class, ['patient' => $patient])
                ->set('form.painDescriptors', ['invalid'])
                ->call('save')
                ->assertHasErrors(['form.painDescriptors.0']);
        });

        test('pain descriptors accepts valid enum values', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(VitalsForm::class, ['patient' => $patient])
                ->set('form.painDescriptors', ['sharp', 'burning'])
                ->call('save')
                ->assertHasNoErrors(['form.painDescriptors']);
        });
    });

    describe('oxygen validation', function () {
        test('sp02 must be at least 0', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(VitalsForm::class, ['patient' => $patient])
                ->set('form.sp02', -1)
                ->call('save')
                ->assertHasErrors(['form.sp02' => 'min']);
        });

        test('sp02 must be at most 100', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(VitalsForm::class, ['patient' => $patient])
                ->set('form.sp02', 101)
                ->call('save')
                ->assertHasErrors(['form.sp02' => 'max']);
        });

        test('oxygen device must be valid enum value', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(VitalsForm::class, ['patient' => $patient])
                ->set('form.oxygenDevice', 'invalid')
                ->call('save')
                ->assertHasErrors(['form.oxygenDevice']);
        });
    });
});

describe('PatientVitalsForm create functionality', function () {
    test('saves vital with all fields', function () {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        Livewire::actingAs($user)
            ->test(VitalsForm::class, ['patient' => $patient])
            ->set('form.temperature', 98.6)
            ->set('form.temperatureSource', 'oral')
            ->set('form.heartRate', 72)
            ->set('form.heartRateSource', 'apical')
            ->set('form.resp', 16)
            ->set('form.systolic', 120)
            ->set('form.diastolic', 80)
            ->set('form.bpSource', 'leftarm')
            ->set('form.bpMethod', 'manual')
            ->set('form.patientPosition', 'sitting')
            ->set('form.abdominalGirth', '85 cm')
            ->set('form.painScale', 'numeric')
            ->set('form.painScore', '3')
            ->set('form.painGoal', 2)
            ->set('form.painLocation', 'Back')
            ->set('form.painDescriptors', ['sharp', 'aching'])
            ->set('form.sp02', 98)
            ->set('form.oxygenDevice', 'ra')
            ->call('save')
            ->assertHasNoErrors();

        $vital = Vital::where('patient_id', $patient->id)->first();

        expect($vital)->not->toBeNull()
            ->and($vital->temperature)->toBe(986)
            ->and($vital->temperature_source)->toBe(Temp::ORAL)
            ->and($vital->heart_rate)->toBe(72)
            ->and($vital->heart_rate_source)->toBe(Heart::APICAL)
            ->and($vital->resp)->toBe(16)
            ->and($vital->systolic)->toBe(120)
            ->and($vital->diastolic)->toBe(80)
            ->and($vital->bp_source)->toBe(BpSource::LEFTARM)
            ->and($vital->bp_method)->toBe(BpMethod::MANUAL)
            ->and($vital->patient_position)->toBe(PatientPosition::SITTING)
            ->and($vital->abdominal_girth)->toBe('85 cm')
            ->and($vital->pain_scale)->toBe(PainScale::NUMERIC)
            ->and($vital->pain_score)->toBe('3')
            ->and($vital->pain_goal)->toBe(2)
            ->and($vital->pain_location)->toBe('Back')
            ->and($vital->pain_descriptors)->toBe(['sharp', 'aching'])
            ->and($vital->sp02)->toBe(98)
            ->and($vital->oxygen_device)->toBe(Oxygen::RA);
    });

    test('saves vital with patient id', function () {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        Livewire::actingAs($user)
            ->test(VitalsForm::class, ['patient' => $patient])
            ->set('form.heartRate', 72)
            ->call('save');

        $vital = Vital::where('patient_id', $patient->id)->first();

        expect($vital->patient_id)->toBe($patient->id);
    });

    test('saves vital with authenticated user id', function () {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        Livewire::actingAs($user)
            ->test(VitalsForm::class, ['patient' => $patient])
            ->set('form.heartRate', 72)
            ->call('save');

        $vital = Vital::where('patient_id', $patient->id)->first();

        expect($vital->user_id)->toBe($user->id);
    });

    test('converts temperature to integer storage format', function () {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        Livewire::actingAs($user)
            ->test(VitalsForm::class, ['patient' => $patient])
            ->set('form.temperature', 98.6)
            ->call('save');

        $vital = Vital::where('patient_id', $patient->id)->first();

        expect($vital->temperature)->toBe(986);
    });

    test('saves vital with nullable fields as null', function () {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        Livewire::actingAs($user)
            ->test(VitalsForm::class, ['patient' => $patient])
            ->call('save');

        $vital = Vital::where('patient_id', $patient->id)->first();

        expect($vital->temperature)->toBeNull()
            ->and($vital->heart_rate)->toBeNull()
            ->and($vital->resp)->toBeNull()
            ->and($vital->systolic)->toBeNull()
            ->and($vital->diastolic)->toBeNull();
    });

    test('resets form after successful save', function () {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        $component = Livewire::actingAs($user)
            ->test(VitalsForm::class, ['patient' => $patient])
            ->set('form.temperature', 98.6)
            ->set('form.heartRate', 72)
            ->call('save');

        $component->assertSet('form.temperature', null)
            ->assertSet('form.heartRate', null);
    });

    test('can create multiple vitals for same patient', function () {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        Livewire::actingAs($user)
            ->test(VitalsForm::class, ['patient' => $patient])
            ->set('form.heartRate', 72)
            ->call('save');

        Livewire::actingAs($user)
            ->test(VitalsForm::class, ['patient' => $patient])
            ->set('form.heartRate', 80)
            ->call('save');

        expect(Vital::where('patient_id', $patient->id)->count())->toBe(2);
    });
});

describe('VitalsForm component', function () {
    test('renders without errors', function () {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        Livewire::actingAs($user)
            ->test(VitalsForm::class, ['patient' => $patient])
            ->assertStatus(200);
    });

    test('receives patient as property', function () {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        Livewire::actingAs($user)
            ->test(VitalsForm::class, ['patient' => $patient])
            ->assertSet('patient.id', $patient->id);
    });

    test('vitals property returns patient vitals ordered by created_at', function () {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        $vital1 = Vital::factory()->for($patient)->create(['created_at' => now()->subHour()]);
        $vital2 = Vital::factory()->for($patient)->create(['created_at' => now()]);

        $component = Livewire::actingAs($user)
            ->test(VitalsForm::class, ['patient' => $patient]);

        $vitals = $component->get('vitals');

        expect($vitals)->toHaveCount(2)
            ->and($vitals->first()->id)->toBe($vital1->id)
            ->and($vitals->last()->id)->toBe($vital2->id);
    });

    test('vitals property includes soft deleted vitals', function () {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        $vital = Vital::factory()->for($patient)->create();
        $vital->update(['deleted_by' => $user->id]);
        $vital->delete();

        $component = Livewire::actingAs($user)
            ->test(VitalsForm::class, ['patient' => $patient]);

        $vitals = $component->get('vitals');

        expect($vitals)->toHaveCount(1)
            ->and($vitals->first()->trashed())->toBeTrue();
    });
});
