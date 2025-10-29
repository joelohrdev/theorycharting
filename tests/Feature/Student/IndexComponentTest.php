<?php

declare(strict_types=1);

use App\Livewire\Student\Index;
use App\Models\User;
use Livewire\Livewire;

describe('Student\Index component rendering', function () {
    test('component renders successfully for teacher', function () {
        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Index::class)
            ->assertStatus(200);
    });

    test('component displays empty state when no students exist', function () {
        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Index::class)
            ->assertSee('There are no active students');
    });

    test('component displays students when they exist', function () {
        $teacher = User::factory()->teacher()->create();
        $student1 = User::factory()->student()->forTeacher($teacher)->create(['name' => 'Alice Student']);
        $student2 = User::factory()->student()->forTeacher($teacher)->create(['name' => 'Bob Student']);

        Livewire::actingAs($teacher)
            ->test(Index::class)
            ->assertSee('Alice Student')
            ->assertSee('Bob Student')
            ->assertSee($student1->email)
            ->assertSee($student2->email);
    });

    test('component only displays teachers own students', function () {
        $teacher1 = User::factory()->teacher()->create();
        $teacher2 = User::factory()->teacher()->create();

        $myStudent = User::factory()->student()->forTeacher($teacher1)->create(['name' => 'My Student']);
        $otherStudent = User::factory()->student()->forTeacher($teacher2)->create(['name' => 'Other Student']);

        Livewire::actingAs($teacher1)
            ->test(Index::class)
            ->assertSee('My Student')
            ->assertDontSee('Other Student');
    });

    test('component does not display soft deleted students', function () {
        $teacher = User::factory()->teacher()->create();
        $activeStudent = User::factory()->student()->forTeacher($teacher)->create(['name' => 'Active Student']);
        $deletedStudent = User::factory()->student()->forTeacher($teacher)->create(['name' => 'Deleted Student']);

        $deletedStudent->delete();

        Livewire::actingAs($teacher)
            ->test(Index::class)
            ->assertSee('Active Student')
            ->assertDontSee('Deleted Student');
    });
});

describe('Student\Index component sorting', function () {
    test('component sorts by name ascending', function () {
        $teacher = User::factory()->teacher()->create();
        User::factory()->student()->forTeacher($teacher)->create(['name' => 'Charlie']);
        User::factory()->student()->forTeacher($teacher)->create(['name' => 'Alice']);
        User::factory()->student()->forTeacher($teacher)->create(['name' => 'Bob']);

        Livewire::actingAs($teacher)
            ->test(Index::class)
            ->call('sort', 'name')
            ->assertSet('sortBy', 'name')
            ->assertSet('sortDirection', 'asc');
    });

    test('component toggles sort direction when clicking same column', function () {
        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Index::class)
            ->set('sortBy', 'name')
            ->set('sortDirection', 'asc')
            ->call('sort', 'name')
            ->assertSet('sortDirection', 'desc');
    });

    test('component resets to ascending when sorting by different column', function () {
        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Index::class)
            ->set('sortBy', 'name')
            ->set('sortDirection', 'desc')
            ->call('sort', 'email')
            ->assertSet('sortBy', 'email')
            ->assertSet('sortDirection', 'asc');
    });

    test('component has default sort by name descending', function () {
        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Index::class)
            ->assertSet('sortBy', 'name')
            ->assertSet('sortDirection', 'desc');
    });
});

describe('Student\Index component pagination', function () {
    test('component paginates students when more than 10 exist', function () {
        $teacher = User::factory()->teacher()->create();
        User::factory()->count(15)->student()->forTeacher($teacher)->create();

        $component = Livewire::actingAs($teacher)
            ->test(Index::class);

        $students = $component->get('students');

        expect($students->total())->toBe(15)
            ->and($students->perPage())->toBe(10)
            ->and($students->count())->toBe(10);
    });

    test('component displays all students when 10 or fewer exist', function () {
        $teacher = User::factory()->teacher()->create();
        User::factory()->count(5)->student()->forTeacher($teacher)->create();

        $component = Livewire::actingAs($teacher)
            ->test(Index::class);

        $students = $component->get('students');

        expect($students->total())->toBe(5)
            ->and($students->count())->toBe(5);
    });
});

describe('Student\Index component event handling', function () {
    test('component refreshes when student-deleted event is dispatched', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create(['name' => 'Test Student']);

        $component = Livewire::actingAs($teacher)
            ->test(Index::class)
            ->assertSee('Test Student');

        $student->delete();

        $component->dispatch('student-deleted')
            ->assertDontSee('Test Student');
    });

    test('component refreshes when student-restored event is dispatched', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create(['name' => 'Restored Student']);

        $student->delete();

        $component = Livewire::actingAs($teacher)
            ->test(Index::class)
            ->assertDontSee('Restored Student');

        $student->restore();

        $component->dispatch('student-restored')
            ->assertSee('Restored Student');
    });
});

describe('Student\Index component data structure', function () {
    test('component selects only necessary columns', function () {
        $teacher = User::factory()->teacher()->create();
        User::factory()->student()->forTeacher($teacher)->create();

        $component = Livewire::actingAs($teacher)
            ->test(Index::class);

        $students = $component->get('students');
        $firstStudent = $students->first();

        expect($firstStudent)->toHaveKeys(['id', 'name', 'email']);
    });
});
