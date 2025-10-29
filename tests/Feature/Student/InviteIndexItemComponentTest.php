<?php

declare(strict_types=1);

use App\Livewire\Student\InviteIndexItem;
use App\Mail\InvitationMail;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

describe('Student\InviteIndexItem component rendering', function () {
    test('component renders successfully with invitation data', function () {
        $teacher = User::factory()->teacher()->create();
        $invitation = Invitation::factory()->forTeacher($teacher)->create([
            'email' => 'student@example.com',
        ]);

        Livewire::actingAs($teacher)
            ->test(InviteIndexItem::class, ['invitation' => $invitation])
            ->assertStatus(200)
            ->assertSee('student@example.com');
    });

    test('component displays invitation email correctly', function () {
        $teacher = User::factory()->teacher()->create();
        $invitation = Invitation::factory()->forTeacher($teacher)->create(['email' => 'test@example.com']);

        Livewire::actingAs($teacher)
            ->test(InviteIndexItem::class, ['invitation' => $invitation])
            ->assertSee('test@example.com');
    });

    test('component displays sent date formatted correctly', function () {
        $teacher = User::factory()->teacher()->create();
        $invitation = Invitation::factory()->forTeacher($teacher)->create([
            'created_at' => now()->subDays(5),
        ]);

        Livewire::actingAs($teacher)
            ->test(InviteIndexItem::class, ['invitation' => $invitation])
            ->assertSee($invitation->created_at->format('F d, Y'));
    });

    test('component displays action buttons', function () {
        $teacher = User::factory()->teacher()->create();
        $invitation = Invitation::factory()->forTeacher($teacher)->create();

        Livewire::actingAs($teacher)
            ->test(InviteIndexItem::class, ['invitation' => $invitation])
            ->assertSee('Resend Invitation')
            ->assertSee('Delete');
    });
});

describe('Student\InviteIndexItem component delete functionality', function () {
    test('component can delete invitation when authorized', function () {
        $teacher = User::factory()->teacher()->create();
        $invitation = Invitation::factory()->forTeacher($teacher)->create();

        Livewire::actingAs($teacher)
            ->test(InviteIndexItem::class, ['invitation' => $invitation])
            ->call('delete')
            ->assertDispatched('invite-deleted');

        expect(Invitation::find($invitation->id))->toBeNull();
    });

    test('component dispatches invite-deleted event after deletion', function () {
        $teacher = User::factory()->teacher()->create();
        $invitation = Invitation::factory()->forTeacher($teacher)->create();

        Livewire::actingAs($teacher)
            ->test(InviteIndexItem::class, ['invitation' => $invitation])
            ->call('delete')
            ->assertDispatched('invite-deleted');
    });

    test('component hard deletes invitation', function () {
        $teacher = User::factory()->teacher()->create();
        $invitation = Invitation::factory()->forTeacher($teacher)->create();
        $invitationId = $invitation->id;

        Livewire::actingAs($teacher)
            ->test(InviteIndexItem::class, ['invitation' => $invitation])
            ->call('delete');

        expect(Invitation::find($invitationId))->toBeNull();
    });
});

describe('Student\InviteIndexItem component resend functionality', function () {
    test('component can resend invitation when authorized', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();
        $invitation = Invitation::factory()->forTeacher($teacher)->create(['email' => 'resend@example.com']);

        Livewire::actingAs($teacher)
            ->test(InviteIndexItem::class, ['invitation' => $invitation])
            ->call('resendInvitation')
            ->assertDispatched('invite-created');

        Mail::assertQueued(InvitationMail::class, function ($mail) {
            return $mail->hasTo('resend@example.com');
        });
    });

    test('component dispatches invite-created event after resending', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();
        $invitation = Invitation::factory()->forTeacher($teacher)->create();

        Livewire::actingAs($teacher)
            ->test(InviteIndexItem::class, ['invitation' => $invitation])
            ->call('resendInvitation')
            ->assertDispatched('invite-created');
    });

    test('component sends email to correct address when resending', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();
        $invitation = Invitation::factory()->forTeacher($teacher)->create(['email' => 'specific@example.com']);

        Livewire::actingAs($teacher)
            ->test(InviteIndexItem::class, ['invitation' => $invitation])
            ->call('resendInvitation');

        Mail::assertQueued(InvitationMail::class, function ($mail) {
            return $mail->hasTo('specific@example.com');
        });
    });
});

describe('Student\InviteIndexItem component authorization for deletion', function () {
    test('teacher cannot delete another teachers invitation', function () {
        $teacher1 = User::factory()->teacher()->create();
        $teacher2 = User::factory()->teacher()->create();
        $invitation = Invitation::factory()->forTeacher($teacher2)->create();

        Livewire::actingAs($teacher1)
            ->test(InviteIndexItem::class, ['invitation' => $invitation])
            ->call('delete')
            ->assertForbidden();

        expect(Invitation::find($invitation->id))->not->toBeNull();
    });

    test('teacher can delete their own invitation', function () {
        $teacher = User::factory()->teacher()->create();
        $invitation = Invitation::factory()->forTeacher($teacher)->create();

        Livewire::actingAs($teacher)
            ->test(InviteIndexItem::class, ['invitation' => $invitation])
            ->call('delete')
            ->assertDispatched('invite-deleted');

        expect(Invitation::find($invitation->id))->toBeNull();
    });

    test('student cannot delete invitation', function () {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create();
        $invitation = Invitation::factory()->forTeacher($teacher)->create();

        Livewire::actingAs($student)
            ->test(InviteIndexItem::class, ['invitation' => $invitation])
            ->call('delete')
            ->assertForbidden();
    });

    test('admin cannot delete teachers invitation', function () {
        $admin = User::factory()->admin()->create();
        $teacher = User::factory()->teacher()->create();
        $invitation = Invitation::factory()->forTeacher($teacher)->create();

        Livewire::actingAs($admin)
            ->test(InviteIndexItem::class, ['invitation' => $invitation])
            ->call('delete')
            ->assertForbidden();
    });
});

describe('Student\InviteIndexItem component authorization for resending', function () {
    test('teacher cannot resend another teachers invitation', function () {
        Mail::fake();

        $teacher1 = User::factory()->teacher()->create();
        $teacher2 = User::factory()->teacher()->create();
        $invitation = Invitation::factory()->forTeacher($teacher2)->create();

        Livewire::actingAs($teacher1)
            ->test(InviteIndexItem::class, ['invitation' => $invitation])
            ->call('resendInvitation')
            ->assertForbidden();

        Mail::assertNothingSent();
    });

    test('teacher can resend their own invitation', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();
        $invitation = Invitation::factory()->forTeacher($teacher)->create();

        Livewire::actingAs($teacher)
            ->test(InviteIndexItem::class, ['invitation' => $invitation])
            ->call('resendInvitation')
            ->assertDispatched('invite-created');

        Mail::assertQueued(InvitationMail::class);
    });

    test('student cannot resend invitation', function () {
        Mail::fake();

        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->forTeacher($teacher)->create();
        $invitation = Invitation::factory()->forTeacher($teacher)->create();

        Livewire::actingAs($student)
            ->test(InviteIndexItem::class, ['invitation' => $invitation])
            ->call('resendInvitation')
            ->assertForbidden();

        Mail::assertNothingSent();
    });
});

describe('Student\InviteIndexItem component locked property', function () {
    test('invitation property is locked and cannot be modified', function () {
        $teacher = User::factory()->teacher()->create();
        $invitation = Invitation::factory()->forTeacher($teacher)->create();
        $otherInvitation = Invitation::factory()->forTeacher($teacher)->create();

        try {
            Livewire::actingAs($teacher)
                ->test(InviteIndexItem::class, ['invitation' => $invitation])
                ->set('invitation', $otherInvitation);

            $this->fail('Expected CannotUpdateLockedPropertyException was not thrown');
        } catch (Exception $e) {
            expect($e->getMessage())->toContain('Cannot update locked property');
        }
    });
});
