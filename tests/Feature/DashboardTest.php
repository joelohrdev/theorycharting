<?php

declare(strict_types=1);

use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response
        ->assertStatus(200)
        ->assertDontSee('Teachers');
});

test('admin can see teachers nav link', function (): void {
    $user = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response
        ->assertStatus(200)
        ->assertSee('Teachers');
});
