<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;

final class StudentController extends Controller
{
    public function show(User $student)
    {
        Gate::authorize('viewStudent', $student);

        return view('student.show', [
            'student' => $student,
        ]);
    }
}
