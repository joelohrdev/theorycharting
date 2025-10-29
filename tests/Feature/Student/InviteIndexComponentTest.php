<?php

declare(strict_types=1);

use App\Livewire\Student\InviteIndex;
use App\Models\Invitation;
use App\Models\User;
use Livewire\Livewire;

describe('Student\InviteIndex component rendering', function () {
    test('component renders successfully for teacher', function () {
        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(InviteIndex::class)
            ->assertStatus(200);
    });

    test('component displays empty state when no invitations exist', function () {
        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(InviteIndex::class)
            ->assertSee('There are no open invitations');
    });

    test('component displays invitations when they exist', function () {
        $teacher = User::factory()->teacher()->create();
        $invitation1 = Invitation::factory()->forTeacher($teacher)->create(['email' => 'student1@example.com']);
        $invitation2 = Invitation::factory()->forTeacher($teacher)->create(['email' => 'student2@example.com']);

        Livewire::actingAs($teacher)
            ->test(InviteIndex::class)
            ->assertSee('student1@example.com')
            ->assertSee('student2@example.com');
    });

    test('component only displays teachers own invitations', function () {
        $teacher1 = User::factory()->teacher()->create();
        $teacher2 = User::factory()->teacher()->create();

        $myInvitation = Invitation::factory()->forTeacher($teacher1)->create(['email' => 'myinvite@example.com']);
        $otherInvitation = Invitation::factory()->forTeacher($teacher2)->create(['email' => 'otherinvite@example.com']);

        Livewire::actingAs($teacher1)
            ->test(InviteIndex::class)
            ->assertSee('myinvite@example.com')
            ->assertDontSee('otherinvite@example.com');
    });

    test('component does not display accepted invitations', function () {
        $teacher = User::factory()->teacher()->create();
        $pendingInvitation = Invitation::factory()->forTeacher($teacher)->create(['email' => 'pending@example.com']);
        $acceptedInvitation = Invitation::factory()->forTeacher($teacher)->accepted()->create(['email' => 'accepted@example.com']);

        Livewire::actingAs($teacher)
            ->test(InviteIndex::class)
            ->assertSee('pending@example.com')
            ->assertDontSee('accepted@example.com');
    });

    test('component displays invitation sent date', function () {
        $teacher = User::factory()->teacher()->create();
        $invitation = Invitation::factory()->forTeacher($teacher)->create([
            'created_at' => now()->subDays(3),
        ]);

        Livewire::actingAs($teacher)
            ->test(InviteIndex::class)
            ->assertSee($invitation->created_at->format('F d, Y'));
    });
});

describe('Student\InviteIndex component sorting', function () {
    test('component sorts by email ascending', function () {
        $teacher = User::factory()->teacher()->create();
        Invitation::factory()->forTeacher($teacher)->create(['email' => 'charlie@example.com']);
        Invitation::factory()->forTeacher($teacher)->create(['email' => 'alice@example.com']);
        Invitation::factory()->forTeacher($teacher)->create(['email' => 'bob@example.com']);

        Livewire::actingAs($teacher)
            ->test(InviteIndex::class)
            ->call('sort', 'email')
            ->assertSet('sortBy', 'email')
            ->assertSet('sortDirection', 'asc');
    });

    test('component toggles sort direction when clicking same column', function () {
        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(InviteIndex::class)
            ->set('sortBy', 'email')
            ->set('sortDirection', 'asc')
            ->call('sort', 'email')
            ->assertSet('sortDirection', 'desc');
    });

    test('component resets to ascending when sorting by different column', function () {
        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(InviteIndex::class)
            ->set('sortBy', 'email')
            ->set('sortDirection', 'desc')
            ->call('sort', 'created_at')
            ->assertSet('sortBy', 'created_at')
            ->assertSet('sortDirection', 'asc');
    });

    test('component has default sort by name descending', function () {
        $teacher = User::factory()->teacher()->create();

        Livewire::actingAs($teacher)
            ->test(InviteIndex::class)
            ->assertSet('sortBy', 'name')
            ->assertSet('sortDirection', 'desc');
    });
});

describe('Student\InviteIndex component pagination', function () {
    test('component paginates invitations when more than 10 exist', function () {
        $teacher = User::factory()->teacher()->create();
        Invitation::factory()->count(15)->forTeacher($teacher)->create();

        $component = Livewire::actingAs($teacher)
            ->test(InviteIndex::class);

        $invitations = $component->get('invites');

        expect($invitations->total())->toBe(15)
            ->and($invitations->perPage())->toBe(10)
            ->and($invitations->count())->toBe(10);
    });

    test('component displays all invitations when 10 or fewer exist', function () {
        $teacher = User::factory()->teacher()->create();
        Invitation::factory()->count(5)->forTeacher($teacher)->create();

        $component = Livewire::actingAs($teacher)
            ->test(InviteIndex::class);

        $invitations = $component->get('invites');

        expect($invitations->total())->toBe(5)
            ->and($invitations->count())->toBe(5);
    });
});

describe('Student\InviteIndex component event handling', function () {
    test('component refreshes when invite-created event is dispatched', function () {
        $teacher = User::factory()->teacher()->create();

        $component = Livewire::actingAs($teacher)
            ->test(InviteIndex::class)
            ->assertSee('There are no open invitations');

        Invitation::factory()->forTeacher($teacher)->create(['email' => 'new@example.com']);

        $component->dispatch('invite-created')
            ->assertSee('new@example.com');
    });

    test('component refreshes when invite-deleted event is dispatched', function () {
        $teacher = User::factory()->teacher()->create();
        $invitation = Invitation::factory()->forTeacher($teacher)->create(['email' => 'delete@example.com']);

        $component = Livewire::actingAs($teacher)
            ->test(InviteIndex::class)
            ->assertSee('delete@example.com');

        $invitation->delete();

        $component->dispatch('invite-deleted')
            ->assertDontSee('delete@example.com');
    });
});

describe('Student\InviteIndex component data structure', function () {
    test('component selects only necessary columns', function () {
        $teacher = User::factory()->teacher()->create();
        Invitation::factory()->forTeacher($teacher)->create();

        $component = Livewire::actingAs($teacher)
            ->test(InviteIndex::class);

        $invitations = $component->get('invites');
        $firstInvitation = $invitations->first();

        expect($firstInvitation)->toHaveKeys(['id', 'email', 'created_at']);
    });
});
