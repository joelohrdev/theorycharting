<?php

declare(strict_types=1);

use App\Livewire\Patient\IntakeOutput;
use App\Models\Intake;
use App\Models\Patient;
use App\Models\User;
use Livewire\Livewire;

describe('PatientIntakeForm validation', function () {
    describe('appetite validation', function () {
        test('appetite must contain valid enum values', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(IntakeOutput::class, ['patient' => $patient])
                ->set('form.appetite', ['invalid'])
                ->call('save')
                ->assertHasErrors(['form.appetite.0']);
        });

        test('appetite accepts valid enum values', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(IntakeOutput::class, ['patient' => $patient])
                ->set('form.appetite', ['good', 'fair'])
                ->call('save')
                ->assertHasNoErrors(['form.appetite']);
        });

        test('appetite comment must not exceed 1000 characters', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(IntakeOutput::class, ['patient' => $patient])
                ->set('form.appetiteComment', str_repeat('a', 1001))
                ->call('save')
                ->assertHasErrors(['form.appetiteComment' => 'max']);
        });
    });

    describe('unable to eat/drink validation', function () {
        test('unable to eat/drink must contain valid enum values', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(IntakeOutput::class, ['patient' => $patient])
                ->set('form.unableToEatDrink', ['invalid'])
                ->call('save')
                ->assertHasErrors(['form.unableToEatDrink.0']);
        });

        test('unable to eat/drink comment must not exceed 1000 characters', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(IntakeOutput::class, ['patient' => $patient])
                ->set('form.unableToEatDrinkComment', str_repeat('a', 1001))
                ->call('save')
                ->assertHasErrors(['form.unableToEatDrinkComment' => 'max']);
        });
    });

    describe('percentage eaten validation', function () {
        test('percentage eaten must be at least 0', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(IntakeOutput::class, ['patient' => $patient])
                ->set('form.percentageEaten', -1)
                ->call('save')
                ->assertHasErrors(['form.percentageEaten' => 'min']);
        });

        test('percentage eaten must be at most 100', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(IntakeOutput::class, ['patient' => $patient])
                ->set('form.percentageEaten', 101)
                ->call('save')
                ->assertHasErrors(['form.percentageEaten' => 'max']);
        });

        test('percentage eaten accepts valid value', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(IntakeOutput::class, ['patient' => $patient])
                ->set('form.percentageEaten', 75)
                ->call('save')
                ->assertHasNoErrors(['form.percentageEaten']);
        });
    });

    describe('liquids validation', function () {
        test('liquids must be at least 0', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(IntakeOutput::class, ['patient' => $patient])
                ->set('form.liquids', -1)
                ->call('save')
                ->assertHasErrors(['form.liquids' => 'min']);
        });

        test('liquids accepts valid value', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(IntakeOutput::class, ['patient' => $patient])
                ->set('form.liquids', 500)
                ->call('save')
                ->assertHasNoErrors(['form.liquids']);
        });
    });

    describe('urine validation', function () {
        test('urine must be at least 0', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(IntakeOutput::class, ['patient' => $patient])
                ->set('form.urine', -1)
                ->call('save')
                ->assertHasErrors(['form.urine' => 'min']);
        });

        test('unmeasured urine occurrence must be at least 0', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(IntakeOutput::class, ['patient' => $patient])
                ->set('form.unmeasuredUrineOccurrence', -1)
                ->call('save')
                ->assertHasErrors(['form.unmeasuredUrineOccurrence' => 'min']);
        });

        test('urine characteristics must contain valid enum values', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(IntakeOutput::class, ['patient' => $patient])
                ->set('form.urineCharacteristics', ['invalid'])
                ->call('save')
                ->assertHasErrors(['form.urineCharacteristics.0']);
        });

        test('urine characteristics accepts valid enum values', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(IntakeOutput::class, ['patient' => $patient])
                ->set('form.urineCharacteristics', ['clear', 'yellow'])
                ->call('save')
                ->assertHasNoErrors(['form.urineCharacteristics']);
        });

        test('urine characteristics comment must not exceed 1000 characters', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(IntakeOutput::class, ['patient' => $patient])
                ->set('form.urineCharacteristicsComment', str_repeat('a', 1001))
                ->call('save')
                ->assertHasErrors(['form.urineCharacteristicsComment' => 'max']);
        });
    });

    describe('stool validation', function () {
        test('stool must be at least 0', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(IntakeOutput::class, ['patient' => $patient])
                ->set('form.stool', -1)
                ->call('save')
                ->assertHasErrors(['form.stool' => 'min']);
        });

        test('unmeasured stool occurrence must be at least 0', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(IntakeOutput::class, ['patient' => $patient])
                ->set('form.unmeasuredStoolOccurrence', -1)
                ->call('save')
                ->assertHasErrors(['form.unmeasuredStoolOccurrence' => 'min']);
        });

        test('stool amount must contain valid enum values', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(IntakeOutput::class, ['patient' => $patient])
                ->set('form.stoolAmount', ['invalid'])
                ->call('save')
                ->assertHasErrors(['form.stoolAmount.0']);
        });

        test('stool amount accepts valid enum values', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(IntakeOutput::class, ['patient' => $patient])
                ->set('form.stoolAmount', ['large', 'medium'])
                ->call('save')
                ->assertHasNoErrors(['form.stoolAmount']);
        });

        test('stool characteristics must contain valid enum values', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(IntakeOutput::class, ['patient' => $patient])
                ->set('form.stoolCharacteristics', ['invalid'])
                ->call('save')
                ->assertHasErrors(['form.stoolCharacteristics.0']);
        });

        test('stool characteristics comment must not exceed 1000 characters', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(IntakeOutput::class, ['patient' => $patient])
                ->set('form.stoolCharacteristicsComment', str_repeat('a', 1001))
                ->call('save')
                ->assertHasErrors(['form.stoolCharacteristicsComment' => 'max']);
        });
    });

    describe('emesis validation', function () {
        test('unmeasured emesis occurrence must be at least 0', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(IntakeOutput::class, ['patient' => $patient])
                ->set('form.unmeasuredEmesisOccurrence', -1)
                ->call('save')
                ->assertHasErrors(['form.unmeasuredEmesisOccurrence' => 'min']);
        });

        test('emesis characteristics must contain valid enum values', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(IntakeOutput::class, ['patient' => $patient])
                ->set('form.emesisCharacteristics', ['invalid'])
                ->call('save')
                ->assertHasErrors(['form.emesisCharacteristics.0']);
        });

        test('emesis amount must contain valid enum values', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(IntakeOutput::class, ['patient' => $patient])
                ->set('form.emesisAmount', ['invalid'])
                ->call('save')
                ->assertHasErrors(['form.emesisAmount.0']);
        });

        test('emesis characteristics comment must not exceed 1000 characters', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(IntakeOutput::class, ['patient' => $patient])
                ->set('form.emesisCharacteristicsComment', str_repeat('a', 1001))
                ->call('save')
                ->assertHasErrors(['form.emesisCharacteristicsComment' => 'max']);
        });

        test('emesis amount comment must not exceed 1000 characters', function () {
            $user = User::factory()->create();
            $patient = Patient::factory()->create();

            Livewire::actingAs($user)
                ->test(IntakeOutput::class, ['patient' => $patient])
                ->set('form.emesisAmountComment', str_repeat('a', 1001))
                ->call('save')
                ->assertHasErrors(['form.emesisAmountComment' => 'max']);
        });
    });
});

describe('PatientIntakeForm create functionality', function () {
    test('saves intake with all fields', function () {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        Livewire::actingAs($user)
            ->test(IntakeOutput::class, ['patient' => $patient])
            ->set('form.appetite', ['good', 'fair'])
            ->set('form.appetiteComment', 'Patient eating well')
            ->set('form.unableToEatDrink', [])
            ->set('form.unableToEatDrinkComment', '')
            ->set('form.percentageEaten', 75)
            ->set('form.liquids', 500)
            ->set('form.urine', 300)
            ->set('form.unmeasuredUrineOccurrence', 2)
            ->set('form.urineCharacteristics', ['clear', 'yellow'])
            ->set('form.urineCharacteristicsComment', 'Normal appearance')
            ->set('form.stool', 250)
            ->set('form.stoolAmount', ['large'])
            ->set('form.stoolAmountComment', 'Large amount')
            ->set('form.unmeasuredStoolOccurrence', 1)
            ->set('form.stoolCharacteristics', [])
            ->set('form.stoolCharacteristicsComment', '')
            ->set('form.unmeasuredEmesisOccurrence', 0)
            ->set('form.emesisCharacteristics', [])
            ->set('form.emesisCharacteristicsComment', '')
            ->set('form.emesisAmount', [])
            ->set('form.emesisAmountComment', '')
            ->call('save')
            ->assertHasNoErrors();

        $intake = Intake::where('patient_id', $patient->id)->first();

        expect($intake)->not->toBeNull()
            ->and($intake->appetite)->toBe(['good', 'fair'])
            ->and($intake->appetite_comment)->toBe('Patient eating well')
            ->and($intake->unable_to_eat_drink)->toBeNull()
            ->and($intake->percentage_eaten)->toBe(75)
            ->and($intake->liquids)->toBe(500)
            ->and($intake->urine)->toBe(300)
            ->and($intake->unmeasured_urine_occurrence)->toBe(2)
            ->and($intake->urine_characteristics)->toBe(['clear', 'yellow'])
            ->and($intake->urine_characteristics_comment)->toBe('Normal appearance')
            ->and($intake->stool)->toBe(250)
            ->and($intake->stool_amount)->toBe(['large'])
            ->and($intake->stool_amount_comment)->toBe('Large amount')
            ->and($intake->unmeasured_stool_occurrence)->toBe(1)
            ->and($intake->stool_characteristics)->toBeNull()
            ->and($intake->unmeasured_emesis_occurrence)->toBe(0)
            ->and($intake->emesis_characteristics)->toBeNull()
            ->and($intake->emesis_amount)->toBeNull();
    });

    test('saves intake with patient id', function () {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        Livewire::actingAs($user)
            ->test(IntakeOutput::class, ['patient' => $patient])
            ->set('form.liquids', 500)
            ->call('save');

        $intake = Intake::where('patient_id', $patient->id)->first();

        expect($intake->patient_id)->toBe($patient->id);
    });

    test('saves intake with authenticated user id', function () {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        Livewire::actingAs($user)
            ->test(IntakeOutput::class, ['patient' => $patient])
            ->set('form.liquids', 500)
            ->call('save');

        $intake = Intake::where('patient_id', $patient->id)->first();

        expect($intake->user_id)->toBe($user->id);
    });

    test('saves intake with nullable fields as null', function () {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        Livewire::actingAs($user)
            ->test(IntakeOutput::class, ['patient' => $patient])
            ->call('save');

        $intake = Intake::where('patient_id', $patient->id)->first();

        expect($intake->appetite)->toBeNull()
            ->and($intake->appetite_comment)->toBeNull()
            ->and($intake->unable_to_eat_drink)->toBeNull()
            ->and($intake->unable_to_eat_drink_comment)->toBeNull()
            ->and($intake->percentage_eaten)->toBeNull()
            ->and($intake->liquids)->toBeNull()
            ->and($intake->urine)->toBeNull()
            ->and($intake->unmeasured_urine_occurrence)->toBeNull()
            ->and($intake->urine_characteristics)->toBeNull()
            ->and($intake->urine_characteristics_comment)->toBeNull()
            ->and($intake->stool)->toBeNull()
            ->and($intake->stool_amount)->toBeNull()
            ->and($intake->stool_amount_comment)->toBeNull()
            ->and($intake->unmeasured_stool_occurrence)->toBeNull()
            ->and($intake->stool_characteristics)->toBeNull()
            ->and($intake->stool_characteristics_comment)->toBeNull()
            ->and($intake->unmeasured_emesis_occurrence)->toBeNull()
            ->and($intake->emesis_characteristics)->toBeNull()
            ->and($intake->emesis_characteristics_comment)->toBeNull()
            ->and($intake->emesis_amount)->toBeNull()
            ->and($intake->emesis_amount_comment)->toBeNull();
    });

    test('resets form after successful save', function () {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        $component = Livewire::actingAs($user)
            ->test(IntakeOutput::class, ['patient' => $patient])
            ->set('form.liquids', 500)
            ->set('form.percentageEaten', 75)
            ->call('save');

        $component->assertSet('form.liquids', null)
            ->assertSet('form.percentageEaten', null);
    });

    test('can create multiple intakes for same patient', function () {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        Livewire::actingAs($user)
            ->test(IntakeOutput::class, ['patient' => $patient])
            ->set('form.liquids', 500)
            ->call('save');

        Livewire::actingAs($user)
            ->test(IntakeOutput::class, ['patient' => $patient])
            ->set('form.liquids', 300)
            ->call('save');

        expect(Intake::where('patient_id', $patient->id)->count())->toBe(2);
    });
});

describe('IntakeOutput component', function () {
    test('renders without errors', function () {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        Livewire::actingAs($user)
            ->test(IntakeOutput::class, ['patient' => $patient])
            ->assertStatus(200);
    });

    test('receives patient as property', function () {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        Livewire::actingAs($user)
            ->test(IntakeOutput::class, ['patient' => $patient])
            ->assertSet('patient.id', $patient->id);
    });

    test('intakes property returns patient intakes ordered by created_at', function () {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        $intake1 = Intake::factory()->for($patient)->create(['created_at' => now()->subHour()]);
        $intake2 = Intake::factory()->for($patient)->create(['created_at' => now()]);

        $component = Livewire::actingAs($user)
            ->test(IntakeOutput::class, ['patient' => $patient]);

        $intakes = $component->get('intakes');

        expect($intakes)->toHaveCount(2)
            ->and($intakes->first()->id)->toBe($intake1->id)
            ->and($intakes->last()->id)->toBe($intake2->id);
    });

    test('intakes property includes soft deleted intakes', function () {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        $intake = Intake::factory()->for($patient)->create();
        $intake->update(['deleted_by' => $user->id]);
        $intake->delete();

        $component = Livewire::actingAs($user)
            ->test(IntakeOutput::class, ['patient' => $patient]);

        $intakes = $component->get('intakes');

        expect($intakes)->toHaveCount(1)
            ->and($intakes->first()->trashed())->toBeTrue();
    });
});
