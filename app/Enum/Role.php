<?php

declare(strict_types=1);

namespace App\Enum;

enum Role: string
{
    case ADMIN = 'admin';
    case AUTHOR = 'author';
    case READER = 'reader';
}
