<?php

declare(strict_types=1);

use App\Livewire\Student\Invite;
use App\Mail\InvitationMail;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

describe('Student\Invite component rendering', function () {
    test('component renders successfully for teacher', function () {
        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->assertStatus(200);
    });

    test('component displays invite button', function () {
        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->assertSee('Invite Student');
    });

    test('component displays modal with form fields', function () {
        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->assertSee('Email Address')
            ->assertSee('Send Invitation');
    });
});

describe('Student\Invite component invitation creation', function () {
    test('component can send invitation with valid email', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.email', 'newstudent@example.com')
            ->call('sendInvite')
            ->assertHasNoErrors()
            ->assertDispatched('invite-created');

        expect(Invitation::where('email', 'newstudent@example.com')->exists())->toBeTrue();
    });

    test('component queues invitation email when invitation is created', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.email', 'newstudent@example.com')
            ->call('sendInvite');

        Mail::assertQueued(InvitationMail::class, function ($mail) {
            return $mail->hasTo('newstudent@example.com');
        });
    });

    test('component creates invitation with correct attributes', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.email', 'test@example.com')
            ->call('sendInvite');

        $invitation = Invitation::where('email', 'test@example.com')->first();

        expect($invitation)->not->toBeNull()
            ->and($invitation->teacher_id)->toBe($teacher->id)
            ->and($invitation->invited_by)->toBe($teacher->id)
            ->and($invitation->role->value)->toBe('student')
            ->and($invitation->expires_at)->not->toBeNull()
            ->and($invitation->token)->not->toBeNull();
    });

    test('component dispatches invite-created event', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.email', 'student@example.com')
            ->call('sendInvite')
            ->assertDispatched('invite-created');
    });

    test('component resets form after successful invitation', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.email', 'student@example.com')
            ->call('sendInvite')
            ->assertSet('form.email', '');
    });
});

describe('Student\Invite component validation', function () {
    test('component requires email field', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.email', '')
            ->call('sendInvite')
            ->assertHasErrors(['form.email' => 'required']);

        expect(Invitation::count())->toBe(0);
    });

    test('component requires valid email format', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.email', 'not-an-email')
            ->call('sendInvite')
            ->assertHasErrors(['form.email' => 'email']);

        expect(Invitation::count())->toBe(0);
    });

    test('component prevents duplicate email with existing user', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();
        $existingStudent = User::factory()->student()->forTeacher($teacher)->create([
            'email' => 'existing@example.com',
        ]);

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.email', 'existing@example.com')
            ->call('sendInvite')
            ->assertHasErrors(['form.email' => 'unique']);
    });

    test('component prevents duplicate email with existing invitation', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();
        Invitation::factory()->forTeacher($teacher)->create([
            'email' => 'pending@example.com',
        ]);

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.email', 'pending@example.com')
            ->call('sendInvite')
            ->assertHasErrors(['form.email' => 'unique']);
    });
});

describe('Student\Invite component authorization', function () {
    test('teacher can create student invitation', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.email', 'student@example.com')
            ->call('sendInvite')
            ->assertHasNoErrors();

        expect(Invitation::where('email', 'student@example.com')->exists())->toBeTrue();
    });

    test('admin can create student invitation', function () {
        Mail::fake();

        $admin = User::factory()->admin()->create();

        Livewire::actingAs($admin)
            ->test(Invite::class)
            ->set('form.email', 'student@example.com')
            ->call('sendInvite')
            ->assertHasNoErrors();

        expect(Invitation::where('email', 'student@example.com')->exists())->toBeTrue();
    });

    test('student cannot create student invitation', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create();

        Livewire::actingAs($student)
            ->test(Invite::class)
            ->set('form.email', 'another@example.com')
            ->call('sendInvite')
            ->assertForbidden();

        expect(Invitation::where('email', 'another@example.com')->exists())->toBeFalse();
    });
});

describe('Student\Invite component invitation expiration', function () {
    test('component creates invitation that expires in 7 days', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.email', 'student@example.com')
            ->call('sendInvite');

        $invitation = Invitation::where('email', 'student@example.com')->first();

        expect(now()->diffInDays($invitation->expires_at, false))->toBeGreaterThanOrEqual(6)
            ->and(now()->diffInDays($invitation->expires_at, false))->toBeLessThanOrEqual(7);
    });
});
