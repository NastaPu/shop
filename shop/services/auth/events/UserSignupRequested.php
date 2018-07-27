<?php

namespace shop\services\auth\events;

use shop\entities\User\User;

class UserSignupRequested
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}