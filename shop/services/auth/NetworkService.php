<?php

namespace shop\services\auth;

use shop\entities\User\Network;
use shop\entities\User\User;
use shop\forms\LoginForm;
use shop\repository\UserRepository;

class NetworkService
{
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function auth($network, $identity): User
    {
        if ($user = $this->users->findByNetworkIdentity($network, $identity)) {
            return $user;
        }
        $user = User::networkSignup($network, $identity);
        $this->users->save($user);
        return $user;
    }

    public function attach($id, $network, $identity):User
    {
        if ($user = $this->users->findByNetworkIdentity($network, $identity)) {
            throw  new \DomainException('This network is already there');
        }
        $user = $this->users->get($id);
        $user->attachNetwork($network, $identity);
        $this->users->save($user);

    }
}
