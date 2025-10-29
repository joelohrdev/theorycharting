<?php

declare(strict_types=1);

use App\Livewire\Student\DeleteIndex;
use App\Models\User;
use Livewire\Livewire;

describe('Student\DeleteIndex component rendering', function () {
    test('component renders successfully for teacher', function () {
        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(DeleteIndex::class)
            ->assertStatus(200);
    });

    test('component displays empty state when no deleted students exist', function () {
        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(DeleteIndex::class)
            ->assertSee('There are no deleted students');
    });

    test('component displays deleted students when they exist', function () {
        $teacher = User::factory()->teacher()->create();
        $student1 = User::factory()->student()->forTeacher($teacher)->create(['name' => 'Deleted Alice']);
        $student2 = User::factory()->student()->forTeacher($teacher)->create(['name' => 'Deleted Bob']);

        $student1->delete();
        $student2->delete();

        Livewire::actingAs($teacher)
            ->test(DeleteIndex::class)
            ->assertSee('Deleted Alice')
            ->assertSee('Deleted Bob')
            ->assertSee($student1->email)
            ->assertSee($student2->email);
    });

    test('component only displays teachers own deleted students', function () {
        $teacher1 = User::factory()->teacher()->create();
        $teacher2 = User::factory()->teacher()->create();

        $myStudent = User::factory()->student()->forTeacher($teacher1)->create(['name' => 'My Deleted']);
        $otherStudent = User::factory()->student()->forTeacher($teacher2)->create(['name' => 'Other Deleted']);

        $myStudent->delete();
        $otherStudent->delete();

        Livewire::actingAs($teacher1)
            ->test(DeleteIndex::class)
            ->assertSee('My Deleted')
            ->assertDontSee('Other Deleted');
    });

    test('component does not display active students', function () {
        $teacher = User::factory()->teacher()->create();
        $activeStudent = User::factory()->student()->forTeacher($teacher)->create(['name' => 'Active Student']);
        $deletedStudent = User::factory()->student()->forTeacher($teacher)->create(['name' => 'Deleted Student']);

        $deletedStudent->delete();

        Livewire::actingAs($teacher)
            ->test(DeleteIndex::class)
            ->assertSee('Deleted Student')
            ->assertDontSee('Active Student');
    });
});

describe('Student\DeleteIndex component sorting', function () {
    test('component sorts by name ascending', function () {
        $teacher = User::factory()->teacher()->create();
        $charlie = User::factory()->student()->forTeacher($teacher)->create(['name' => 'Charlie']);
        $alice = User::factory()->student()->forTeacher($teacher)->create(['name' => 'Alice']);
        $bob = User::factory()->student()->forTeacher($teacher)->create(['name' => 'Bob']);

        $charlie->delete();
        $alice->delete();
        $bob->delete();

        Livewire::actingAs($teacher)
            ->test(DeleteIndex::class)
            ->call('sort', 'name')
            ->assertSet('sortBy', 'name')
            ->assertSet('sortDirection', 'asc');
    });

    test('component toggles sort direction when clicking same column', function () {
        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(DeleteIndex::class)
            ->set('sortBy', 'name')
            ->set('sortDirection', 'asc')
            ->call('sort', 'name')
            ->assertSet('sortDirection', 'desc');
    });

    test('component resets to ascending when sorting by different column', function () {
        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(DeleteIndex::class)
            ->set('sortBy', 'name')
            ->set('sortDirection', 'desc')
            ->call('sort', 'email')
            ->assertSet('sortBy', 'email')
            ->assertSet('sortDirection', 'asc');
    });

    test('component has default sort by name descending', function () {
        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(DeleteIndex::class)
            ->assertSet('sortBy', 'name')
            ->assertSet('sortDirection', 'desc');
    });
});

describe('Student\DeleteIndex component pagination', function () {
    test('component paginates deleted students when more than 10 exist', function () {
        $teacher = User::factory()->teacher()->create();
        $students = User::factory()->count(15)->student()->forTeacher($teacher)->create();

        $students->each->delete();

        $component = Livewire::actingAs($teacher)
            ->test(DeleteIndex::class);

        $deletedStudents = $component->get('deletedStudents');

        expect($deletedStudents->total())->toBe(15)
            ->and($deletedStudents->perPage())->toBe(10)
            ->and($deletedStudents->count())->toBe(10);
    });

    test('component displays all deleted students when 10 or fewer exist', function () {
        $teacher = User::factory()->teacher()->create();
        $students = User::factory()->count(5)->student()->forTeacher($teacher)->create();

        $students->each->delete();

        $component = Livewire::actingAs($teacher)
            ->test(DeleteIndex::class);

        $deletedStudents = $component->get('deletedStudents');

        expect($deletedStudents->total())->toBe(5)
            ->and($deletedStudents->count())->toBe(5);
    });
});

describe('Student\DeleteIndex component event handling', function () {
    test('component refreshes when student-restored event is dispatched', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create(['name' => 'Restore Me']);

        $student->delete();

        $component = Livewire::actingAs($teacher)
            ->test(DeleteIndex::class)
            ->assertSee('Restore Me');

        $student->restore();

        $component->dispatch('student-restored')
            ->assertDontSee('Restore Me');
    });
});

describe('Student\DeleteIndex component data structure', function () {
    test('component selects only necessary columns', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create();

        $student->delete();

        $component = Livewire::actingAs($teacher)
            ->test(DeleteIndex::class);

        $deletedStudents = $component->get('deletedStudents');
        $firstStudent = $deletedStudents->first();

        expect($firstStudent)->toHaveKeys(['id', 'name', 'email']);
    });
});
