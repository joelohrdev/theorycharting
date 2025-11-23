<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Livewire\Student\Invite;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

describe('StudentForm validation through Invite component', function () {
    test('form requires studentId field', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.studentId', '')
            ->call('sendInvite')
            ->assertHasErrors(['form.studentId' => 'required']);
    });

    test('form validates unique studentId against users table', function () {
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

    test('form validates unique studentId against invitations table', function () {
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

    test('form accepts valid unique studentId', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.studentId', '9999999')
            ->call('sendInvite')
            ->assertHasNoErrors();

        expect(Invitation::where('email', '9999999@student.techcampus.org')->exists())->toBeTrue();
    });
});

describe('StudentForm save functionality', function () {
    test('form creates invitation with correct email from studentId', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.studentId', '1234567')
            ->call('sendInvite');

        $invitation = Invitation::where('email', '1234567@student.techcampus.org')->first();

        expect($invitation->email)->toBe('1234567@student.techcampus.org');
    });

    test('form creates invitation with student role', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.studentId', '2345678')
            ->call('sendInvite');

        $invitation = Invitation::where('email', '2345678@student.techcampus.org')->first();

        expect($invitation->role)->toBe(Role::STUDENT);
    });

    test('form creates invitation with authenticated user as inviter', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.studentId', '3456789')
            ->call('sendInvite');

        $invitation = Invitation::where('email', '3456789@student.techcampus.org')->first();

        expect($invitation->invited_by)->toBe($teacher->id);
    });

    test('form creates invitation with authenticated user as teacher', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.studentId', '4567890')
            ->call('sendInvite');

        $invitation = Invitation::where('email', '4567890@student.techcampus.org')->first();

        expect($invitation->teacher_id)->toBe($teacher->id);
    });

    test('form creates invitation with generated token', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.studentId', '5678901')
            ->call('sendInvite');

        $invitation = Invitation::where('email', '5678901@student.techcampus.org')->first();

        expect($invitation->token)->not->toBeNull()
            ->and(mb_strlen($invitation->token))->toBe(64);
    });

    test('form creates invitation with 7 day expiration', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.studentId', '6789012')
            ->call('sendInvite');

        $invitation = Invitation::where('email', '6789012@student.techcampus.org')->first();

        expect(now()->diffInDays($invitation->expires_at, false))->toBeGreaterThanOrEqual(6)
            ->and(now()->diffInDays($invitation->expires_at, false))->toBeLessThanOrEqual(7);
    });

    test('form creates invitation that is not accepted', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.studentId', '7890123')
            ->call('sendInvite');

        $invitation = Invitation::where('email', '7890123@student.techcampus.org')->first();

        expect($invitation->accepted_at)->toBeNull();
    });

    test('form persists invitation to database', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.studentId', '8901234')
            ->call('sendInvite');

        $invitation = Invitation::where('email', '8901234@student.techcampus.org')->first();

        expect($invitation)->not->toBeNull()
            ->and(Invitation::where('email', '8901234@student.techcampus.org')->exists())->toBeTrue();
    });

    test('form returns invitation instance that is persisted', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.studentId', '9012345')
            ->call('sendInvite');

        $invitation = Invitation::where('email', '9012345@student.techcampus.org')->first();

        expect($invitation)->toBeInstanceOf(Invitation::class);
    });
});

describe('StudentForm multiple invitations', function () {
    test('form can create multiple invitations for same teacher', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.studentId', '1111111')
            ->call('sendInvite');

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.studentId', '2222222')
            ->call('sendInvite');

        expect(Invitation::where('teacher_id', $teacher->id)->count())->toBe(2);
    });

    test('form generates unique tokens for each invitation', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.studentId', '3333333')
            ->call('sendInvite');

        $invitation1 = Invitation::where('email', '3333333@student.techcampus.org')->first();

        Livewire::actingAs($teacher)
            ->test(Invite::class)
            ->set('form.studentId', '4444444')
            ->call('sendInvite');

        $invitation2 = Invitation::where('email', '4444444@student.techcampus.org')->first();

        expect($invitation1->token)->not->toBe($invitation2->token);
    });
});
