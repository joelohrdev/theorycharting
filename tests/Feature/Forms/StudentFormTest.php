<?php

declare(strict_types=1);

use App\enums\Role;
use App\Livewire\Student\Invite;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

describe('StudentForm validation through Invite component', function () {
    test('form requires email field', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.email', '')
            ->call('sendInvite')
            ->assertHasErrors(['form.email' => 'required']);
    });

    test('form requires valid email format', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.email', 'not-an-email')
            ->call('sendInvite')
            ->assertHasErrors(['form.email' => 'email']);
    });

    test('form validates unique email against users table', function () {
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

    test('form validates unique email against invitations table', function () {
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

    test('form accepts valid unique email', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.email', 'valid@example.com')
            ->call('sendInvite')
            ->assertHasNoErrors();

        expect(Invitation::where('email', 'valid@example.com')->exists())->toBeTrue();
    });
});

describe('StudentForm save functionality', function () {
    test('form creates invitation with correct email', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.email', 'newstudent@example.com')
            ->call('sendInvite');

        $invitation = Invitation::where('email', 'newstudent@example.com')->first();

        expect($invitation->email)->toBe('newstudent@example.com');
    });

    test('form creates invitation with student role', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.email', 'student@example.com')
            ->call('sendInvite');

        $invitation = Invitation::where('email', 'student@example.com')->first();

        expect($invitation->role)->toBe(Role::STUDENT);
    });

    test('form creates invitation with authenticated user as inviter', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.email', 'student@example.com')
            ->call('sendInvite');

        $invitation = Invitation::where('email', 'student@example.com')->first();

        expect($invitation->invited_by)->toBe($teacher->id);
    });

    test('form creates invitation with authenticated user as teacher', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.email', 'student@example.com')
            ->call('sendInvite');

        $invitation = Invitation::where('email', 'student@example.com')->first();

        expect($invitation->teacher_id)->toBe($teacher->id);
    });

    test('form creates invitation with generated token', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.email', 'student@example.com')
            ->call('sendInvite');

        $invitation = Invitation::where('email', 'student@example.com')->first();

        expect($invitation->token)->not->toBeNull()
            ->and(mb_strlen($invitation->token))->toBe(64);
    });

    test('form creates invitation with 7 day expiration', function () {
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

    test('form creates invitation that is not accepted', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.email', 'student@example.com')
            ->call('sendInvite');

        $invitation = Invitation::where('email', 'student@example.com')->first();

        expect($invitation->accepted_at)->toBeNull();
    });

    test('form persists invitation to database', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.email', 'persisted@example.com')
            ->call('sendInvite');

        $invitation = Invitation::where('email', 'persisted@example.com')->first();

        expect($invitation)->not->toBeNull()
            ->and(Invitation::where('email', 'persisted@example.com')->exists())->toBeTrue();
    });

    test('form returns invitation instance that is persisted', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.email', 'student@example.com')
            ->call('sendInvite');

        $invitation = Invitation::where('email', 'student@example.com')->first();

        expect($invitation)->toBeInstanceOf(Invitation::class);
    });
});

describe('StudentForm multiple invitations', function () {
    test('form can create multiple invitations for same teacher', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.email', 'student1@example.com')
            ->call('sendInvite');

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.email', 'student2@example.com')
            ->call('sendInvite');

        expect(Invitation::where('teacher_id', $teacher->id)->count())->toBe(2);
    });

    test('form generates unique tokens for each invitation', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.email', 'student1@example.com')
            ->call('sendInvite');

        $invitation1 = Invitation::where('email', 'student1@example.com')->first();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.email', 'student2@example.com')
            ->call('sendInvite');

        $invitation2 = Invitation::where('email', 'student2@example.com')->first();

        expect($invitation1->token)->not->toBe($invitation2->token);
    });
});
