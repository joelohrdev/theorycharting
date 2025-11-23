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
            ->assertSee('Student ID')
            ->assertSee('Send Invitation');
    });
});

describe('Student\Invite component invitation creation', function () {
    test('component can send invitation with valid studentId', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.studentId', '1234567')
            ->call('sendInvite')
            ->assertHasNoErrors()
            ->assertDispatched('invite-created');

        expect(Invitation::where('email', '1234567@student.techcampus.org')->exists())->toBeTrue();
    });

    test('component queues invitation email when invitation is created', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.studentId', '2345678')
            ->call('sendInvite');

        Mail::assertQueued(InvitationMail::class, function ($mail) {
            return $mail->hasTo('2345678@student.techcampus.org');
        });
    });

    test('component creates invitation with correct attributes', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.studentId', '3456789')
            ->call('sendInvite');

        $invitation = Invitation::where('email', '3456789@student.techcampus.org')->first();

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
            ->set('form.studentId', '4567890')
            ->call('sendInvite')
            ->assertDispatched('invite-created');
    });

    test('component resets form after successful invitation', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.studentId', '5678901')
            ->call('sendInvite')
            ->assertSet('form.studentId', '');
    });
});

describe('Student\Invite component validation', function () {
    test('component requires studentId field', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.studentId', '')
            ->call('sendInvite')
            ->assertHasErrors(['form.studentId' => 'required']);

        expect(Invitation::count())->toBe(0);
    });

    test('component prevents duplicate studentId with existing user', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();
        $existingStudent = User::factory()->student()->forTeacher($teacher)->create([
            'email' => '1234567@student.techcampus.org',
        ]);

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.studentId', '1234567')
            ->call('sendInvite')
            ->assertHasErrors(['form.studentId']);
    });

    test('component prevents duplicate studentId with existing invitation', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();
        Invitation::factory()->forTeacher($teacher)->create([
            'email' => '7654321@student.techcampus.org',
        ]);

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.studentId', '7654321')
            ->call('sendInvite')
            ->assertHasErrors(['form.studentId']);
    });
});

describe('Student\Invite component authorization', function () {
    test('teacher can create student invitation', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.studentId', '1111111')
            ->call('sendInvite')
            ->assertHasNoErrors();

        expect(Invitation::where('email', '1111111@student.techcampus.org')->exists())->toBeTrue();
    });

    test('admin can create student invitation', function () {
        Mail::fake();

        $admin = User::factory()->admin()->create();

        Livewire::actingAs($admin)
            ->test(Invite::class)
            ->set('form.studentId', '2222222')
            ->call('sendInvite')
            ->assertHasNoErrors();

        expect(Invitation::where('email', '2222222@student.techcampus.org')->exists())->toBeTrue();
    });

    test('student cannot create student invitation', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create();

        Livewire::actingAs($student)
            ->test(Invite::class)
            ->set('form.studentId', '3333333')
            ->call('sendInvite')
            ->assertForbidden();

        expect(Invitation::where('email', '3333333@student.techcampus.org')->exists())->toBeFalse();
    });
});

describe('Student\Invite component invitation expiration', function () {
    test('component creates invitation that expires in 7 days', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.studentId', '4444444')
            ->call('sendInvite');

        $invitation = Invitation::where('email', '4444444@student.techcampus.org')->first();

        expect(now()->diffInDays($invitation->expires_at, false))->toBeGreaterThanOrEqual(6)
            ->and(now()->diffInDays($invitation->expires_at, false))->toBeLessThanOrEqual(7);
    });
});
