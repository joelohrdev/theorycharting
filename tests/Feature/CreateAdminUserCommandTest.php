<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Models\User;

use function Pest\Laravel\artisan;

test('creates admin user with valid input', function () {
    artisan('app:create-admin-user')
        ->expectsQuestion('What is the admin\'s name?', 'Admin User')
        ->expectsQuestion('What is the admin\'s email?', 'admin@example.com')
        ->expectsQuestion('Set a password', 'password123')
        ->expectsQuestion('Confirm password', 'password123')
        ->expectsQuestion('Create admin user with this information?', true)
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
    artisan('app:create-admin-user')
        ->expectsQuestion('What is the admin\'s name?', 'Admin User')
        ->expectsQuestion('What is the admin\'s email?', 'admin@example.com')
        ->expectsQuestion('Set a password', 'password123')
        ->expectsQuestion('Confirm password', 'differentpassword')
        ->expectsOutput('Passwords do not match.')
        ->assertFailed();

    expect(User::where('email', 'admin@example.com')->exists())->toBeFalse();
});

test('validates email uniqueness', function () {
    User::factory()->create(['email' => 'existing@example.com']);

    artisan('app:create-admin-user')
        ->expectsQuestion('What is the admin\'s name?', 'Admin User')
        ->expectsQuestion('What is the admin\'s email?', 'existing@example.com')
        ->doesntExpectOutput('Admin user created successfully!');
});

test('cancels creation when not confirmed', function () {
    artisan('app:create-admin-user')
        ->expectsQuestion('What is the admin\'s name?', 'Admin User')
        ->expectsQuestion('What is the admin\'s email?', 'admin@example.com')
        ->expectsQuestion('Set a password', 'password123')
        ->expectsQuestion('Confirm password', 'password123')
        ->expectsQuestion('Create admin user with this information?', false)
        ->expectsOutput('Admin user creation cancelled.')
        ->assertFailed();

    expect(User::where('email', 'admin@example.com')->exists())->toBeFalse();
});
