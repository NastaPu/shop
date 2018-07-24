<?php

namespace shop\services\auth;

use shop\access\Rbac;
use shop\entities\User\User;
use shop\forms\auth\SignupForm;
use shop\repositories\UserRepository;
use shop\services\manage\TransactionManager;
use shop\services\RoleManager;
use Yii;

class SignupService
{
    private $transaction;
    private $roles;
    private $users;

    public function __construct(RoleManager $roles, TransactionManager $transaction, UserRepository $users)
    {
        $this->transaction = $transaction;
        $this->roles = $roles;
        $this->users = $users;
    }

    public function signup(SignupForm $form):void
    {
        $user = User::signup(
            $form->username,
            $form->email,
            $form->password
        );

        $this->transaction->wrap(function () use ($user) {
            $this->users->save($user);
            $this->roles->assign($user->id, Rbac::ROLE_USER);
        });

        $send = Yii::$app->mailer
            ->compose(
                ['html' => 'emailConfirmToken-html', 'text' => 'emailConfirmToken-text'],
                ['user' => $user]
            )
            ->setTo($form->email)
            ->setFrom(Yii::$app->params['supportEmail'])
            ->setSubject('Signup confirm for ' . \Yii::$app->name)
            ->send();
        if(!$send) {
            throw new \RuntimeException('Sending error');
        }


        //return $user;
    }

    public function confirm($token): void
    {
        if (empty($token)) {
            throw new \DomainException('Empty confirm token ');
        }
        $user = User::findOne(['email_confirm_token' => $token]);
        if (!$user) {
            throw new \DomainException('User is not found ');
        }
        $user->confirmSignup();
        if (!$user->save()) {
            throw new \RuntimeException('Saving error ');
        }
    }
}
