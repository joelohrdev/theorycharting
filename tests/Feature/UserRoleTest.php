<?php

declare(strict_types=1);

use App\enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('User roles', function () {
    test('admin users can be created', function () {
        $admin = User::factory()->admin()->create();

        expect($admin->is_admin)->toBeTrue()
            ->and($admin->role)->toBeNull()
            ->and($admin->teacher_id)->toBeNull();
    });

    test('teacher users can be created', function () {
        $teacher = User::factory()->teacher()->create();

        expect($teacher->is_admin)->toBeFalse()
            ->and($teacher->role)->toBe(Role::TEACHER)
            ->and($teacher->teacher_id)->toBeNull();
    });

    test('student users can be created', function () {
        $student = User::factory()->student()->create();

        expect($student->is_admin)->toBeFalse()
            ->and($student->role)->toBe(Role::STUDENT);
    });

    test('student can be assigned to a teacher', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->forTeacher($teacher)->create();

        expect($student->teacher_id)->toBe($teacher->id)
            ->and($student->role)->toBe(Role::STUDENT);
    });
});

describe('User role helper methods', function () {
    test('isTeacher returns true for teachers', function () {
        $teacher = User::factory()->teacher()->create();

        expect($teacher->isTeacher())->toBeTrue();
    });

    test('isTeacher returns false for non-teachers', function () {
        $student = User::factory()->student()->create();
        $admin = User::factory()->admin()->create();

        expect($student->isTeacher())->toBeFalse()
            ->and($admin->isTeacher())->toBeFalse();
    });

    test('isStudent returns true for students', function () {
        $student = User::factory()->student()->create();

        expect($student->isStudent())->toBeTrue();
    });

    test('isStudent returns false for non-students', function () {
        $teacher = User::factory()->teacher()->create();
        $admin = User::factory()->admin()->create();

        expect($teacher->isStudent())->toBeFalse()
            ->and($admin->isStudent())->toBeFalse();
    });
});

describe('User relationships', function () {
    test('teacher can have multiple students', function () {
        $teacher = User::factory()->teacher()->create();
        $students = User::factory()
            ->count(3)
            ->forTeacher($teacher)
            ->create();

        expect($teacher->students)->toHaveCount(3)
            ->and($teacher->students->pluck('id')->sort()->values()->all())
            ->toBe($students->pluck('id')->sort()->values()->all());
    });

    test('student belongs to a teacher', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->forTeacher($teacher)->create();

        expect($student->teacher)->not->toBeNull()
            ->and($student->teacher->id)->toBe($teacher->id);
    });

    test('teacher has no teacher assigned', function () {
        $teacher = User::factory()->teacher()->create();

        expect($teacher->teacher)->toBeNull();
    });
});

describe('User helper methods', function () {
    test('initials returns correct initials for two-word name', function () {
        $user = User::factory()->create(['name' => 'John Doe']);

        expect($user->initials())->toBe('JD');
    });

    test('initials returns correct initials for single-word name', function () {
        $user = User::factory()->create(['name' => 'John']);

        expect($user->initials())->toBe('J');
    });

    test('initials returns correct initials for multi-word name', function () {
        $user = User::factory()->create(['name' => 'John Michael Doe']);

        expect($user->initials())->toBe('JM');
    });
});
