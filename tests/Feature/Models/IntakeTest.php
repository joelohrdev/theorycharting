<?php

declare(strict_types=1);

use App\Models\Intake;
use App\Models\Patient;
use App\Models\User;

describe('Intake model', function () {
    test('can be created using factory', function () {
        $intake = Intake::factory()->create();

        expect($intake)->toBeInstanceOf(Intake::class)
            ->and($intake->exists)->toBeTrue();
    });

    test('belongs to a patient', function () {
        $patient = Patient::factory()->create();
        $intake = Intake::factory()->for($patient)->create();

        expect($intake->patient)->toBeInstanceOf(Patient::class)
            ->and($intake->patient->id)->toBe($patient->id);
    });

    test('belongs to a user', function () {
        $user = User::factory()->create();
        $intake = Intake::factory()->create(['user_id' => $user->id]);

        expect($intake->user)->toBeInstanceOf(User::class)
            ->and($intake->user->id)->toBe($user->id);
    });

    test('can have a deleted by user', function () {
        $user = User::factory()->create();
        $intake = Intake::factory()->create(['deleted_by' => $user->id]);

        expect($intake->deletedBy)->toBeInstanceOf(User::class)
            ->and($intake->deletedBy->id)->toBe($user->id);
    });

    test('uses soft deletes', function () {
        $intake = Intake::factory()->create();
        $intakeId = $intake->id;

        $intake->delete();

        expect(Intake::find($intakeId))->toBeNull()
            ->and(Intake::withTrashed()->find($intakeId))->not->toBeNull();
    });
});

describe('Intake model casts', function () {
    test('casts appetite to array', function () {
        $appetite = ['good', 'fair'];
        $intake = Intake::factory()->create(['appetite' => $appetite]);

        expect($intake->appetite)->toBeArray()
            ->and($intake->appetite)->toBe($appetite);
    });

    test('casts unable_to_eat_drink to array', function () {
        $reasons = ['nausea', 'pain'];
        $intake = Intake::factory()->create(['unable_to_eat_drink' => $reasons]);

        expect($intake->unable_to_eat_drink)->toBeArray()
            ->and($intake->unable_to_eat_drink)->toBe($reasons);
    });

    test('casts urine_characteristics to array', function () {
        $characteristics = ['clear', 'yellow'];
        $intake = Intake::factory()->create(['urine_characteristics' => $characteristics]);

        expect($intake->urine_characteristics)->toBeArray()
            ->and($intake->urine_characteristics)->toBe($characteristics);
    });

    test('casts stool_amount to array', function () {
        $amounts = ['large', 'medium'];
        $intake = Intake::factory()->create(['stool_amount' => $amounts]);

        expect($intake->stool_amount)->toBeArray()
            ->and($intake->stool_amount)->toBe($amounts);
    });

    test('casts stool_characteristics to array', function () {
        $characteristics = ['formed', 'soft'];
        $intake = Intake::factory()->create(['stool_characteristics' => $characteristics]);

        expect($intake->stool_characteristics)->toBeArray()
            ->and($intake->stool_characteristics)->toBe($characteristics);
    });

    test('casts emesis_characteristics to array', function () {
        $characteristics = ['clear', 'green'];
        $intake = Intake::factory()->create(['emesis_characteristics' => $characteristics]);

        expect($intake->emesis_characteristics)->toBeArray()
            ->and($intake->emesis_characteristics)->toBe($characteristics);
    });

    test('casts emesis_amount to array', function () {
        $amounts = ['large', 'copious'];
        $intake = Intake::factory()->create(['emesis_amount' => $amounts]);

        expect($intake->emesis_amount)->toBeArray()
            ->and($intake->emesis_amount)->toBe($amounts);
    });

    test('casts integer fields correctly', function () {
        $intake = Intake::factory()->create([
            'percentage_eaten' => 75,
            'liquids' => 500,
            'urine' => 300,
            'unmeasured_urine_occurrence' => 2,
            'stool' => 250,
            'unmeasured_stool_occurrence' => 1,
            'unmeasured_emesis_occurrence' => 3,
        ]);

        expect($intake->percentage_eaten)->toBeInt()
            ->and($intake->liquids)->toBeInt()
            ->and($intake->urine)->toBeInt()
            ->and($intake->unmeasured_urine_occurrence)->toBeInt()
            ->and($intake->stool)->toBeInt()
            ->and($intake->unmeasured_stool_occurrence)->toBeInt()
            ->and($intake->unmeasured_emesis_occurrence)->toBeInt();
    });
});

describe('Intake patient relationship', function () {
    test('patient can have multiple intakes', function () {
        $patient = Patient::factory()->create();
        $intakes = Intake::factory()->count(3)->for($patient)->create();

        expect($patient->intakes)->toHaveCount(3)
            ->and($patient->intakes->first())->toBeInstanceOf(Intake::class);
    });

    test('deleting patient cascades to intakes', function () {
        $patient = Patient::factory()->create();
        $intake = Intake::factory()->for($patient)->create();
        $intakeId = $intake->id;

        $patient->delete();

        expect(Intake::withTrashed()->find($intakeId))->toBeNull();
    });
});
