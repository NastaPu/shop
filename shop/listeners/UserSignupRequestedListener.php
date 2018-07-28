<?php

namespace shop\listeners;

use shop\entities\User\events\UserSignUpRequested;
use Yii;
use yii\mail\MailerInterface;

class UserSignupRequestedListener
{
    private $mailer;
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function handle(UserSignUpRequested $event): void
    {
        $send = $this->mailer
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