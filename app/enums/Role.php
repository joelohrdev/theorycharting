<?php

declare(strict_types=1);

namespace App\enums;

enum Role: string
{
    case TEACHER = 'teacher';
    case STUDENT = 'student';
}
