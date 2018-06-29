<?php

namespace shop\services\auth;

use shop\entities\User\User;
use shop\forms\SignupForm;
use Yii;

class SignupService
{
    public function signup(SignupForm $form):void
    {
        $user = User::signup(
            $form->username,
            $form->email,
            $form->password
        );

        if (!$user->save()) {
            throw new \RuntimeException('Saving error');
        }
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
