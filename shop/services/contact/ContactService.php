<?php

namespace shop\services\contact;

use shop\forms\ContactForm;
use yii\mail\MailerInterface;
use Yii;

class ContactService
{
    //private $adminEmail;
    private $mailer;

   /* public function __construct(MailerInterface $mailer)
    {
       // $this->adminEmail = $adminEmail;
        $this->mailer = $mailer;
    }*/

    public function send(ContactForm $form)
    {

        $send = Yii::$app->mailer->compose()
            ->setTo(Yii::$app->params['adminEmail'])
            ->setFrom($form->email)
            ->setSubject($form->subject)
            ->setTextBody($form->body)
            ->send();

        if(!$send) {
            throw new \RuntimeException('Sending error');
        }
    }
}