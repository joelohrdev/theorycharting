<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it can access teachers if an admin', function () {
    $user = User::factory()->admin()->create();
    $response = $this->actingAs($user)->get(route('teacher.index'));

    $response->assertOk();
});

test('it cannot access teachers if an admin', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $response = $this->actingAs($user)->get(route('teacher.index'));

    $response->assertStatus(403);
});
