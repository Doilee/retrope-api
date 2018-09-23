<?php

namespace App\Observers;

use App\User;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserObserver
{
    public function creating(User $user)
    {
        // if (!$user->email) $user->email = uniqid();
    }
}
