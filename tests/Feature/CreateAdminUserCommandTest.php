<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Models\User;
use Laravel\Prompts\Prompt;

use function Pest\Laravel\artisan;

test('creates admin user with valid input', function () {
    Prompt::fake([
        'Admin User',
        'admin@example.com',
        'password123',
        'password123',
        true,
    ]);

    artisan('app:create-admin-user')
        ->assertSuccessful()
        ->expectsOutput('Admin user created successfully!');

    $admin = User::where('email', 'admin@example.com')->first();

    expect($admin)->not->toBeNull()
        ->and($admin->name)->toBe('Admin User')
        ->and($admin->is_admin)->toBeTrue()
        ->and($admin->role)->toBe(Role::TEACHER)
        ->and($admin->email_verified_at)->not->toBeNull();
});

test('fails when passwords do not match', function () {
    Prompt::fake([
        'Admin User',
        'admin@example.com',
        'password123',
        'differentpassword',
    ]);

    artisan('app:create-admin-user')
        ->expectsOutput('Passwords do not match.')
        ->assertFailed();

    expect(User::where('email', 'admin@example.com')->exists())->toBeFalse();
});

test('validates email uniqueness', function () {
    User::factory()->create(['email' => 'existing@example.com']);

    Prompt::fake([
        'Admin User',
        'existing@example.com',
    ]);

    artisan('app:create-admin-user')
        ->doesntExpectOutput('Admin user created successfully!');
});

test('cancels creation when not confirmed', function () {
    Prompt::fake([
        'Admin User',
        'admin@example.com',
        'password123',
        'password123',
        false,
    ]);

    artisan('app:create-admin-user')
        ->expectsOutput('Admin user creation cancelled.')
        ->assertFailed();

    expect(User::where('email', 'admin@example.com')->exists())->toBeFalse();
});
