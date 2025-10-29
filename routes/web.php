<?php

declare(strict_types=1);

use App\Http\Controllers\AcceptInvitationController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::get('/invitation/{token}', AcceptInvitationController::class)->name('invitation.accept');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('teachers', 'teacher.index')->middleware('can:viewTeacherPages')->name('teacher.index');
    Route::view('teachers/invites', 'teacher.invites')->middleware('can:viewTeacherPages')->name('teacher.invites');
    Route::view('students', 'student.index')->middleware('can:viewStudentPages')->name('student.index');
    Route::view('students/invites', 'student.invites')->middleware('can:viewStudentPages')->name('student.invites');
    Route::view('students/deleted', 'student.deleted')->middleware('can:viewStudentPages')->name('student.deleted');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
