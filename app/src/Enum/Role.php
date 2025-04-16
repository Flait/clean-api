<?php

namespace App\Enum;

enum Role: string
{
    case Admin = 'admin';
    case Author = 'author';
    case Reader = 'reader';
}
