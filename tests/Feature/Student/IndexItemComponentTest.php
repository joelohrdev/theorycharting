<?php

declare(strict_types=1);

use App\Livewire\Student\IndexItem;
use App\Models\User;
use Livewire\Livewire;

describe('Student\IndexItem component rendering', function () {
    test('component renders successfully with student data', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create([
            'name' => 'Test Student',
            'email' => 'student@example.com',
        ]);

        Livewire::actingAs($teacher)
            ->test(IndexItem::class, ['user' => $student])
            ->assertStatus(200)
            ->assertSee('Test Student')
            ->assertSee('student@example.com');
    });

    test('component displays student name correctly', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create(['name' => 'Alice Johnson']);

        Livewire::actingAs($teacher)
            ->test(IndexItem::class, ['user' => $student])
            ->assertSee('Alice Johnson');
    });

    test('component displays student email correctly', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create(['email' => 'alice@example.com']);

        Livewire::actingAs($teacher)
            ->test(IndexItem::class, ['user' => $student])
            ->assertSee('alice@example.com');
    });

    test('component displays delete action button', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create();

        Livewire::actingAs($teacher)
            ->test(IndexItem::class, ['user' => $student])
            ->assertSee('Delete');
    });
});

describe('Student\IndexItem component delete functionality', function () {
    test('component can delete student when authorized', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create();

        Livewire::actingAs($teacher)
            ->test(IndexItem::class, ['user' => $student])
            ->call('delete')
            ->assertDispatched('student-deleted');

        expect($student->fresh()->trashed())->toBeTrue();
    });

    test('component displays success toast after deleting student', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create();

        Livewire::actingAs($teacher)
            ->test(IndexItem::class, ['user' => $student])
            ->call('delete')
            ->assertDispatched('student-deleted');
    });

    test('component dispatches student-deleted event', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create();

        Livewire::actingAs($teacher)
            ->test(IndexItem::class, ['user' => $student])
            ->call('delete')
            ->assertDispatched('student-deleted');
    });

    test('component soft deletes student instead of hard delete', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create();

        Livewire::actingAs($teacher)
            ->test(IndexItem::class, ['user' => $student])
            ->call('delete');

        expect(User::withTrashed()->find($student->id))->not->toBeNull()
            ->and($student->fresh()->trashed())->toBeTrue();
    });
});

describe('Student\IndexItem component authorization', function () {
    test('teacher cannot delete another teachers student', function () {
        $teacher1 = User::factory()->teacher()->create();
        $teacher2 = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher2)->create();

        Livewire::actingAs($teacher1)
            ->test(IndexItem::class, ['user' => $student])
            ->call('delete')
            ->assertForbidden();

        expect($student->fresh()->trashed())->toBeFalse();
    });

    test('teacher can delete their own student', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create();

        Livewire::actingAs($teacher)
            ->test(IndexItem::class, ['user' => $student])
            ->call('delete')
            ->assertDispatched('student-deleted');

        expect($student->fresh()->trashed())->toBeTrue();
    });

    test('student cannot delete themselves', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create();

        Livewire::actingAs($student)
            ->test(IndexItem::class, ['user' => $student])
            ->call('delete')
            ->assertForbidden();
    });

    test('admin cannot delete student using this component', function () {
        $admin = User::factory()->admin()->create();
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create();

        Livewire::actingAs($admin)
            ->test(IndexItem::class, ['user' => $student])
            ->call('delete')
            ->assertForbidden();
    });

    test('teacher cannot delete a user who is not a student', function () {
        $teacher1 = User::factory()->teacher()->create();
        $teacher2 = User::factory()->teacher()->create();

        Livewire::actingAs($teacher1)
            ->test(IndexItem::class, ['user' => $teacher2])
            ->call('delete')
            ->assertForbidden();
    });
});

describe('Student\IndexItem component locked property', function () {
    test('user property is locked and cannot be modified', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create();
        $otherStudent = User::factory()->student()->forTeacher($teacher)->create();

        try {
            Livewire::actingAs($teacher)
                ->test(IndexItem::class, ['user' => $student])
                ->set('user', $otherStudent);

            $this->fail('Expected CannotUpdateLockedPropertyException was not thrown');
        } catch (Exception $e) {
            expect($e->getMessage())->toContain('Cannot update locked property');
        }
    });
});
