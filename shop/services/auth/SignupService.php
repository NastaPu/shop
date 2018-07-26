<?php

namespace shop\services\auth;

use shop\access\Rbac;
use shop\entities\User\User;
use shop\forms\auth\SignupForm;
use shop\repositories\UserRepository;
use shop\services\manage\TransactionManager;
use shop\services\newsletter\Newsletter;
use shop\services\RoleManager;
use Yii;

class SignupService
{
    private $transaction;
    private $roles;
    private $users;
    private $newsletter;

    public function __construct(RoleManager $roles, TransactionManager $transaction, UserRepository $users, Newsletter $newsletter)
    {
        $this->transaction = $transaction;
        $this->roles = $roles;
        $this->users = $users;
        $this->newsletter = $newsletter;
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
        $user =  $this->users->getByEmailConfirmToken($token);
        $user->confirmSignup();
        if (!$user->save()) {
            throw new \RuntimeException('Saving error ');
        }

        $this->newsletter->subscribe($user->email);
    }
}
