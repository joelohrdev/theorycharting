<?php

declare(strict_types=1);

use App\Livewire\Patient\Adl;
use App\Models\Adl as AdlModel;
use App\Models\Patient;
use App\Models\User;
use Livewire\Livewire;

describe('PatientAdlForm validation', function () {
    describe('bathing validation', function () {
        test('bathing must contain valid enum values', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(Adl::class, ['patient' => $patient])
                ->set('form.bathing', ['invalid'])
                ->set('form.observations', 'Test observation')
                ->call('save')
                ->assertHasErrors(['form.bathing.0']);
        });

        test('bathing accepts valid enum values', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(Adl::class, ['patient' => $patient])
                ->set('form.bathing', ['shower', 'completedbedbath'])
                ->set('form.observations', 'Test observation')
                ->call('save')
                ->assertHasNoErrors(['form.bathing']);
        });

        test('bathing comment must not exceed 1000 characters', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(Adl::class, ['patient' => $patient])
                ->set('form.bathingComment', str_repeat('a', 1001))
                ->set('form.observations', 'Test observation')
                ->call('save')
                ->assertHasErrors(['form.bathingComment' => 'max']);
        });

        test('bathing level of assist must contain valid enum values', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(Adl::class, ['patient' => $patient])
                ->set('form.bathingLevelOfAssist', ['invalid'])
                ->set('form.observations', 'Test observation')
                ->call('save')
                ->assertHasErrors(['form.bathingLevelOfAssist.0']);
        });

        test('bathing level of assist comment must not exceed 1000 characters', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(Adl::class, ['patient' => $patient])
                ->set('form.bathingLevelOfAssistComment', str_repeat('a', 1001))
                ->set('form.observations', 'Test observation')
                ->call('save')
                ->assertHasErrors(['form.bathingLevelOfAssistComment' => 'max']);
        });
    });

    describe('oral care validation', function () {
        test('oral care must contain valid enum values', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(Adl::class, ['patient' => $patient])
                ->set('form.oralCare', ['invalid'])
                ->set('form.observations', 'Test observation')
                ->call('save')
                ->assertHasErrors(['form.oralCare.0']);
        });

        test('oral care comment must not exceed 1000 characters', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(Adl::class, ['patient' => $patient])
                ->set('form.oralCareComment', str_repeat('a', 1001))
                ->set('form.observations', 'Test observation')
                ->call('save')
                ->assertHasErrors(['form.oralCareComment' => 'max']);
        });
    });

    describe('observations validation', function () {
        test('observations must not exceed 5000 characters', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(Adl::class, ['patient' => $patient])
                ->set('form.observations', str_repeat('a', 5001))
                ->call('save')
                ->assertHasErrors(['form.observations' => 'max']);
        });

        test('observations accepts valid value', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(Adl::class, ['patient' => $patient])
                ->set('form.observations', 'Patient is doing well today')
                ->call('save')
                ->assertHasNoErrors(['form.observations']);
        });

        test('observations can be null', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(Adl::class, ['patient' => $patient])
                ->set('form.observations', '')
                ->call('save')
                ->assertHasNoErrors(['form.observations']);
        });
    });

    describe('activity and mobility validation', function () {
        test('activity must contain valid enum values', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(Adl::class, ['patient' => $patient])
                ->set('form.activity', ['invalid'])
                ->set('form.observations', 'Test observation')
                ->call('save')
                ->assertHasErrors(['form.activity.0']);
        });

        test('activity accepts valid enum values', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(Adl::class, ['patient' => $patient])
                ->set('form.activity', ['upadlib', 'uptochair'])
                ->set('form.observations', 'Test observation')
                ->call('save')
                ->assertHasNoErrors(['form.activity']);
        });

        test('mobility must contain valid enum values', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(Adl::class, ['patient' => $patient])
                ->set('form.mobility', ['invalid'])
                ->set('form.observations', 'Test observation')
                ->call('save')
                ->assertHasErrors(['form.mobility.0']);
        });

        test('assistive device must contain valid enum values', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(Adl::class, ['patient' => $patient])
                ->set('form.assistiveDevice', ['invalid'])
                ->set('form.observations', 'Test observation')
                ->call('save')
                ->assertHasErrors(['form.assistiveDevice.0']);
        });

        test('assistive device accepts valid enum values', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(Adl::class, ['patient' => $patient])
                ->set('form.assistiveDevice', ['cane', 'wheelchair'])
                ->set('form.observations', 'Test observation')
                ->call('save')
                ->assertHasNoErrors(['form.assistiveDevice']);
        });

        test('distance ambulated must be at least 0', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(Adl::class, ['patient' => $patient])
                ->set('form.distanceAmbulated', -1)
                ->set('form.observations', 'Test observation')
                ->call('save')
                ->assertHasErrors(['form.distanceAmbulated' => 'min']);
        });

        test('distance ambulated accepts valid value', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(Adl::class, ['patient' => $patient])
                ->set('form.distanceAmbulated', 50)
                ->set('form.observations', 'Test observation')
                ->call('save')
                ->assertHasNoErrors(['form.distanceAmbulated']);
        });
    });

    describe('skin and hygiene validation', function () {
        test('skin must contain valid enum values', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(Adl::class, ['patient' => $patient])
                ->set('form.skin', ['invalid'])
                ->set('form.observations', 'Test observation')
                ->call('save')
                ->assertHasErrors(['form.skin.0']);
        });

        test('hair must contain valid enum values', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(Adl::class, ['patient' => $patient])
                ->set('form.hair', ['invalid'])
                ->set('form.observations', 'Test observation')
                ->call('save')
                ->assertHasErrors(['form.hair.0']);
        });

        test('shave must contain valid enum values', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(Adl::class, ['patient' => $patient])
                ->set('form.shave', ['invalid'])
                ->set('form.observations', 'Test observation')
                ->call('save')
                ->assertHasErrors(['form.shave.0']);
        });

        test('nail care must contain valid enum values', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(Adl::class, ['patient' => $patient])
                ->set('form.nailCare', ['invalid'])
                ->set('form.observations', 'Test observation')
                ->call('save')
                ->assertHasErrors(['form.nailCare.0']);
        });
    });

    describe('positioning and repositioning validation', function () {
        test('repositioned must contain valid enum values', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(Adl::class, ['patient' => $patient])
                ->set('form.repositioned', ['invalid'])
                ->set('form.observations', 'Test observation')
                ->call('save')
                ->assertHasErrors(['form.repositioned.0']);
        });

        test('level of repositioning assistance must contain valid enum values', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(Adl::class, ['patient' => $patient])
                ->set('form.levelOfRepositioningAssistance', ['invalid'])
                ->set('form.observations', 'Test observation')
                ->call('save')
                ->assertHasErrors(['form.levelOfRepositioningAssistance.0']);
        });

        test('positioning frequency must contain valid enum values', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(Adl::class, ['patient' => $patient])
                ->set('form.positioningFrequency', ['invalid'])
                ->set('form.observations', 'Test observation')
                ->call('save')
                ->assertHasErrors(['form.positioningFrequency.0']);
        });

        test('head of bed elevated must contain valid enum values', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(Adl::class, ['patient' => $patient])
                ->set('form.headOfBedElevated', ['invalid'])
                ->set('form.observations', 'Test observation')
                ->call('save')
                ->assertHasErrors(['form.headOfBedElevated.0']);
        });
    });

    describe('anti-embolism validation', function () {
        test('anti embolism device must contain valid enum values', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(Adl::class, ['patient' => $patient])
                ->set('form.antiEmbolismDevice', ['invalid'])
                ->set('form.observations', 'Test observation')
                ->call('save')
                ->assertHasErrors(['form.antiEmbolismDevice.0']);
        });

        test('anti embolism status must contain valid enum values', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(Adl::class, ['patient' => $patient])
                ->set('form.antiEmbolismStatus', ['invalid'])
                ->set('form.observations', 'Test observation')
                ->call('save')
                ->assertHasErrors(['form.antiEmbolismStatus.0']);
        });

        test('anti embolism device comment must not exceed 1000 characters', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(Adl::class, ['patient' => $patient])
                ->set('form.antiEmbolismDeviceComment', str_repeat('a', 1001))
                ->set('form.observations', 'Test observation')
                ->call('save')
                ->assertHasErrors(['form.antiEmbolismDeviceComment' => 'max']);
        });
    });
});

describe('PatientAdlForm create functionality', function () {
    test('saves adl with all fields', function () {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        Livewire::actingAs($user)
            ->test(Adl::class, ['patient' => $patient])
            ->set('form.bathing', ['shower', 'completedbedbath'])
            ->set('form.bathingComment', 'Patient bathed in shower')
            ->set('form.bathingLevelOfAssist', ['independent'])
            ->set('form.bathingLevelOfAssistComment', 'No assistance needed')
            ->set('form.oralCare', [])
            ->set('form.oralCareComment', '')
            ->set('form.oralCareLevelOfAssist', [])
            ->set('form.oralCareLevelOfAssistComment', '')
            ->set('form.linenChange', [])
            ->set('form.linenChangeComment', '')
            ->set('form.hair', [])
            ->set('form.hairComment', '')
            ->set('form.shave', [])
            ->set('form.shaveComment', '')
            ->set('form.deodorant', [])
            ->set('form.deodorantComment', '')
            ->set('form.nailCare', [])
            ->set('form.nailCareComment', '')
            ->set('form.skin', [])
            ->set('form.skinComment', '')
            ->set('form.observations', 'Patient is doing well today')
            ->set('form.activity', ['upadlib'])
            ->set('form.activityComment', 'Moving freely')
            ->set('form.mobility', [])
            ->set('form.mobilityComment', '')
            ->set('form.levelOfTransferAssist', [])
            ->set('form.levelOfTransferAssistComment', '')
            ->set('form.assistiveDevice', [])
            ->set('form.assistiveDeviceComment', '')
            ->set('form.distanceAmbulated', 50)
            ->set('form.ambulationResponse', [])
            ->set('form.ambulationResponseComment', '')
            ->set('form.levelOfRepositioningAssistance', [])
            ->set('form.levelOfRepositioningAssistanceComment', '')
            ->set('form.repositioned', [])
            ->set('form.repositionedComment', '')
            ->set('form.positioningFrequency', [])
            ->set('form.positioningFrequencyComment', '')
            ->set('form.headOfBedElevated', [])
            ->set('form.headOfBedElevatedComment', '')
            ->set('form.heelsFeet', [])
            ->set('form.heelsFeetComment', '')
            ->set('form.handsArms', [])
            ->set('form.handsArmsComment', '')
            ->set('form.rangeOfMotion', [])
            ->set('form.rangeOfMotionComment', '')
            ->set('form.transportMethod', [])
            ->set('form.transportMethodComment', '')
            ->set('form.antiEmbolismDevice', [])
            ->set('form.antiEmbolismDeviceComment', '')
            ->set('form.antiEmbolismStatus', [])
            ->set('form.antiEmbolismStatusComment', '')
            ->call('save')
            ->assertHasNoErrors();

        $adl = AdlModel::where('patient_id', $patient->id)->first();

        expect($adl)->not->toBeNull()
            ->and($adl->bathing)->toBe(['shower', 'completedbedbath'])
            ->and($adl->bathing_comment)->toBe('Patient bathed in shower')
            ->and($adl->bathing_level_of_assist)->toBe(['independent'])
            ->and($adl->bathing_level_of_assist_comment)->toBe('No assistance needed')
            ->and($adl->observations)->toBe('Patient is doing well today')
            ->and($adl->activity)->toBe(['upadlib'])
            ->and($adl->activity_comment)->toBe('Moving freely')
            ->and($adl->distance_ambulated)->toBe(50);
    });

    test('saves adl with patient id', function () {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        Livewire::actingAs($user)
            ->test(Adl::class, ['patient' => $patient])
            ->set('form.observations', 'Patient is doing well')
            ->call('save');

        $adl = AdlModel::where('patient_id', $patient->id)->first();

        expect($adl->patient_id)->toBe($patient->id);
    });

    test('saves adl with authenticated user id', function () {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        Livewire::actingAs($user)
            ->test(Adl::class, ['patient' => $patient])
            ->set('form.observations', 'Patient is doing well')
            ->call('save');

        $adl = AdlModel::where('patient_id', $patient->id)->first();

        expect($adl->user_id)->toBe($user->id);
    });

    test('saves adl with nullable fields as null', function () {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        Livewire::actingAs($user)
            ->test(Adl::class, ['patient' => $patient])
            ->set('form.observations', 'Patient is doing well')
            ->call('save');

        $adl = AdlModel::where('patient_id', $patient->id)->first();

        expect($adl->bathing_comment)->toBeNull()
            ->and($adl->bathing_level_of_assist_comment)->toBeNull()
            ->and($adl->oral_care_comment)->toBeNull()
            ->and($adl->oral_care_level_of_assist_comment)->toBeNull()
            ->and($adl->linen_change_comment)->toBeNull()
            ->and($adl->hair_comment)->toBeNull()
            ->and($adl->shave_comment)->toBeNull()
            ->and($adl->deodorant_comment)->toBeNull()
            ->and($adl->nail_care_comment)->toBeNull()
            ->and($adl->skin_comment)->toBeNull()
            ->and($adl->activity_comment)->toBeNull()
            ->and($adl->mobility_comment)->toBeNull()
            ->and($adl->distance_ambulated)->toBeNull();
    });

    test('resets form after successful save', function () {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        $component = Livewire::actingAs($user)
            ->test(Adl::class, ['patient' => $patient])
            ->set('form.observations', 'Patient is doing well')
            ->set('form.distanceAmbulated', 50)
            ->set('form.bathing', ['shower'])
            ->call('save');

        $component->assertSet('form.observations', '')
            ->assertSet('form.distanceAmbulated', null)
            ->assertSet('form.bathing', []);
    });

    test('can create multiple adls for same patient', function () {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        Livewire::actingAs($user)
            ->test(Adl::class, ['patient' => $patient])
            ->set('form.observations', 'First observation')
            ->call('save');

        Livewire::actingAs($user)
            ->test(Adl::class, ['patient' => $patient])
            ->set('form.observations', 'Second observation')
            ->call('save');

        expect(AdlModel::where('patient_id', $patient->id)->count())->toBe(2);
    });
});

describe('Adl component', function () {
    test('renders without errors', function () {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        Livewire::actingAs($user)
            ->test(Adl::class, ['patient' => $patient])
            ->assertStatus(200);
    });

    test('receives patient as property', function () {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        Livewire::actingAs($user)
            ->test(Adl::class, ['patient' => $patient])
            ->assertSet('patient.id', $patient->id);
    });

    test('adls property returns patient adls ordered by created_at', function () {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        $adl1 = AdlModel::factory()->for($patient)->create(['created_at' => now()->subHour()]);
        $adl2 = AdlModel::factory()->for($patient)->create(['created_at' => now()]);

        $component = Livewire::actingAs($user)
            ->test(Adl::class, ['patient' => $patient]);

        $adls = $component->get('adls');

        expect($adls)->toHaveCount(2)
            ->and($adls->first()->id)->toBe($adl1->id)
            ->and($adls->last()->id)->toBe($adl2->id);
    });

    test('adls property includes soft deleted adls', function () {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        $adl = AdlModel::factory()->for($patient)->create();
        $adl->update(['deleted_by' => $user->id]);
        $adl->delete();

        $component = Livewire::actingAs($user)
            ->test(Adl::class, ['patient' => $patient]);

        $adls = $component->get('adls');

        expect($adls)->toHaveCount(1)
            ->and($adls->first()->trashed())->toBeTrue();
    });
});
