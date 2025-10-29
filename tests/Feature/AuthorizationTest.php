<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Teacher index authorization', function () {
    test('admin can access teacher index', function () {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('teacher.index'));

        $response->assertOk();
    });

    test('teacher cannot access teacher index', function () {
        $teacher = User::factory()->teacher()->create();

        $response = $this->actingAs($teacher)->get(route('teacher.index'));

        $response->assertForbidden();
    });

    test('student cannot access teacher index', function () {
        $student = User::factory()->student()->create();

        $response = $this->actingAs($student)->get(route('teacher.index'));

        $response->assertForbidden();
    });

    test('guest cannot access teacher index', function () {
        $response = $this->get(route('teacher.index'));

        $response->assertRedirect(route('login'));
    });
});

describe('Teacher invites authorization', function () {
    test('admin can access teacher invites', function () {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('teacher.invites'));

        $response->assertOk();
    });

    test('teacher cannot access teacher invites', function () {
        $teacher = User::factory()->teacher()->create();

        $response = $this->actingAs($teacher)->get(route('teacher.invites'));

        $response->assertForbidden();
    });

    test('student cannot access teacher invites', function () {
        $student = User::factory()->student()->create();

        $response = $this->actingAs($student)->get(route('teacher.invites'));

        $response->assertForbidden();
    });

    test('guest cannot access teacher invites', function () {
        $response = $this->get(route('teacher.invites'));

        $response->assertRedirect(route('login'));
    });
});

describe('Student index authorization', function () {
    test('admin can access student index', function () {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('student.index'));

        $response->assertOk();
    });

    test('teacher can access student index', function () {
        $teacher = User::factory()->teacher()->create();

        $response = $this->actingAs($teacher)->get(route('student.index'));

        $response->assertOk();
    });

    test('student cannot access student index', function () {
        $student = User::factory()->student()->create();

        $response = $this->actingAs($student)->get(route('student.index'));

        $response->assertStatus(403);
    });

    test('guest cannot access student index', function () {
        $response = $this->get(route('student.index'));

        $response->assertRedirect(route('login'));
    });
});

describe('Dashboard authorization', function () {
    test('admin can access dashboard', function () {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertOk();
    });

    test('teacher can access dashboard', function () {
        $teacher = User::factory()->teacher()->create();

        $response = $this->actingAs($teacher)->get(route('dashboard'));

        $response->assertOk();
    });

    test('student can access dashboard', function () {
        $student = User::factory()->student()->create();

        $response = $this->actingAs($student)->get(route('dashboard'));

        $response->assertOk();
    });

    test('guest cannot access dashboard', function () {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    });
});
