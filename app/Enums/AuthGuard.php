<?php

namespace App\Enums;

enum AuthGuard: string
{
    case Web = 'web';
    case Admins = 'admins';
}
