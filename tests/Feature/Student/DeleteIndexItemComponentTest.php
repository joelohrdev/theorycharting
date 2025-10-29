<?php

declare(strict_types=1);

use App\Livewire\Student\DeleteIndexItem;
use App\Models\User;
use Livewire\Livewire;

describe('Student\DeleteIndexItem component rendering', function () {
    test('component renders successfully with deleted student data', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create([
            'name' => 'Deleted Student',
            'email' => 'deleted@example.com',
        ]);

        $student->delete();

        Livewire::actingAs($teacher)
            ->test(DeleteIndexItem::class, ['user' => $student])
            ->assertStatus(200)
            ->assertSee('Deleted Student')
            ->assertSee('deleted@example.com');
    });

    test('component displays student name correctly', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create(['name' => 'Bob Deleted']);

        $student->delete();

        Livewire::actingAs($teacher)
            ->test(DeleteIndexItem::class, ['user' => $student])
            ->assertSee('Bob Deleted');
    });

    test('component displays student email correctly', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create(['email' => 'bob@example.com']);

        $student->delete();

        Livewire::actingAs($teacher)
            ->test(DeleteIndexItem::class, ['user' => $student])
            ->assertSee('bob@example.com');
    });

    test('component displays restore action button', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create();

        $student->delete();

        Livewire::actingAs($teacher)
            ->test(DeleteIndexItem::class, ['user' => $student])
            ->assertSee('Restore');
    });
});

describe('Student\DeleteIndexItem component restore functionality', function () {
    test('component can restore student when authorized', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create();

        $student->delete();

        Livewire::actingAs($teacher)
            ->test(DeleteIndexItem::class, ['user' => $student])
            ->call('restoreStudent')
            ->assertDispatched('student-restored');

        expect($student->fresh()->trashed())->toBeFalse();
    });

    test('component displays success toast after restoring student', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create();

        $student->delete();

        Livewire::actingAs($teacher)
            ->test(DeleteIndexItem::class, ['user' => $student])
            ->call('restoreStudent')
            ->assertDispatched('student-restored');
    });

    test('component dispatches student-restored event', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create();

        $student->delete();

        Livewire::actingAs($teacher)
            ->test(DeleteIndexItem::class, ['user' => $student])
            ->call('restoreStudent')
            ->assertDispatched('student-restored');
    });

    test('component restores student to active state', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create();

        $student->delete();
        expect($student->fresh()->trashed())->toBeTrue();

        Livewire::actingAs($teacher)
            ->test(DeleteIndexItem::class, ['user' => $student])
            ->call('restoreStudent');

        expect($student->fresh()->trashed())->toBeFalse();
    });
});

describe('Student\DeleteIndexItem component authorization', function () {
    test('teacher cannot restore another teachers student', function () {
        $teacher1 = User::factory()->teacher()->create();
        $teacher2 = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher2)->create();

        $student->delete();

        Livewire::actingAs($teacher1)
            ->test(DeleteIndexItem::class, ['user' => $student])
            ->call('restoreStudent')
            ->assertForbidden();

        expect($student->fresh()->trashed())->toBeTrue();
    });

    test('teacher can restore their own student', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create();

        $student->delete();

        Livewire::actingAs($teacher)
            ->test(DeleteIndexItem::class, ['user' => $student])
            ->call('restoreStudent')
            ->assertDispatched('student-restored');

        expect($student->fresh()->trashed())->toBeFalse();
    });

    test('student cannot restore themselves', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create();

        $student->delete();

        Livewire::actingAs($student)
            ->test(DeleteIndexItem::class, ['user' => $student])
            ->call('restoreStudent')
            ->assertForbidden();
    });

    test('admin cannot restore student using this component', function () {
        $admin = User::factory()->admin()->create();
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create();

        $student->delete();

        Livewire::actingAs($admin)
            ->test(DeleteIndexItem::class, ['user' => $student])
            ->call('restoreStudent')
            ->assertForbidden();
    });

    test('teacher cannot restore a user who is not a student', function () {
        $teacher1 = User::factory()->teacher()->create();
        $teacher2 = User::factory()->teacher()->create();

        $teacher2->delete();

        Livewire::actingAs($teacher1)
            ->test(DeleteIndexItem::class, ['user' => $teacher2])
            ->call('restoreStudent')
            ->assertForbidden();
    });
});

describe('Student\DeleteIndexItem component locked property', function () {
    test('user property is locked and cannot be modified', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create();
        $otherStudent = User::factory()->student()->forTeacher($teacher)->create();

        $student->delete();

        try {
            Livewire::actingAs($teacher)
                ->test(DeleteIndexItem::class, ['user' => $student])
                ->set('user', $otherStudent);

            $this->fail('Expected CannotUpdateLockedPropertyException was not thrown');
        } catch (Exception $e) {
            expect($e->getMessage())->toContain('Cannot update locked property');
        }
    });
});
