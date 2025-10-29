<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Student index page', function () {
    test('admin can see students heading', function () {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('student.index'));

        $response->assertOk()
            ->assertSee('Students');
    });

    test('teacher can see students heading', function () {
        $teacher = User::factory()->teacher()->create();

        $response = $this->actingAs($teacher)->get(route('student.index'));

        $response->assertOk()
            ->assertSee('Students')
            ->assertSee('Students');
    });

    test('student cannot see students heading', function () {
        $student = User::factory()->student()->create();

        $response = $this->actingAs($student)->get(route('student.index'));

        $response->assertStatus(403);
    });
});

describe('Student visibility and filtering', function () {
    test('teacher only sees their own students', function () {
        $teacher1 = User::factory()->teacher()->create(['name' => 'Teacher One']);
        $teacher2 = User::factory()->teacher()->create(['name' => 'Teacher Two']);

        $teacher1Students = User::factory()->count(3)->forTeacher($teacher1)->create();
        $teacher2Students = User::factory()->count(2)->forTeacher($teacher2)->create();

        $response = $this->actingAs($teacher1)->get(route('student.index'));

        $response->assertOk();

        // Should see their own students
        foreach ($teacher1Students as $student) {
            $response->assertSee($student->name);
        }

        // Should NOT see other teacher's students
        foreach ($teacher2Students as $student) {
            $response->assertDontSee($student->name);
        }
    });

    test('teacher with no students sees empty list', function () {
        $teacher = User::factory()->teacher()->create();

        $response = $this->actingAs($teacher)->get(route('student.index'));

        $response->assertOk()
            ->assertSee('Students');
    });
});

describe('Navigation visibility', function () {
    test('admin sees students nav link in sidebar', function () {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertOk()
            ->assertSee('Students');
    });

    test('teacher sees students nav link in sidebar', function () {
        $teacher = User::factory()->teacher()->create();

        $response = $this->actingAs($teacher)->get(route('dashboard'));

        $response->assertOk()
            ->assertSee('Students');
    });

    test('student does not see teachers nav link in sidebar', function () {
        $student = User::factory()->student()->create();

        $response = $this->actingAs($student)->get(route('dashboard'));

        $response->assertOk()
            ->assertDontSee('Teachers');
    });

    test('admin sees both teachers and students nav links', function () {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertOk()
            ->assertSee('Teachers')
            ->assertSee('Students');
    });
});
