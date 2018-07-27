<?php

namespace shop\services\auth;

use shop\access\Rbac;
use shop\dispatcher\EventDispatcher;
use shop\entities\User\User;
use shop\forms\auth\SignupForm;
use shop\repositories\UserRepository;
use shop\services\auth\events\UserSignupRequested;
use shop\services\manage\TransactionManager;
use shop\services\RoleManager;

class SignupService
{
    private $transaction;
    private $roles;
    private $users;
    private $dispatcher;

    public function __construct(
        RoleManager $roles,
        TransactionManager $transaction,
        UserRepository $users,
        EventDispatcher $dispatcher
    ) {
        $this->transaction = $transaction;
        $this->roles = $roles;
        $this->users = $users;
        $this->dispatcher = $dispatcher;
    }

    public function signup(SignupForm $form):void
    {
        $user = User::signup(
            $form->username,
            $form->email,
            $form->phone,
            $form->password
        );

        $this->transaction->wrap(function () use ($user) {
            $this->users->save($user);
            $this->roles->assign($user->id, Rbac::ROLE_USER);
        });

        $this->dispatcher->dispatch(new UserSignupRequested($user));
    }

    public function confirm($token): void
    {
        if (empty($token)) {
            throw new \DomainException('Empty confirm token');
        }
        $user =  $this->users->getByEmailConfirmToken($token);
        $user->confirmSignup();
        if (!$user->save()) {
            throw new \RuntimeException('Saving error');
        }
    }
}
