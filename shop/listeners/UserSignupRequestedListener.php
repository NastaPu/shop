<?php

namespace shop\listeners;

use shop\entities\User\events\UserSignUpRequested;
use Yii;

class UserSignupRequestedListener
{
    public function handle(UserSignUpRequested $event): void
    {
        $send = Yii::$app->mailer
            ->compose(
                ['html' => 'emailConfirmToken-html', 'text' => 'emailConfirmToken-text'],
                ['user' => $event->user]
            )
            ->setTo($event->user->email)
            ->setFrom(Yii::$app->params['supportEmail'])
            ->setSubject('Signup confirm')
            ->send();
        if (!$send) {
            throw new \RuntimeException('Email sending error');
        }
    }
}