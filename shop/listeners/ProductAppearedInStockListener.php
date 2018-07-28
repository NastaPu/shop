<?php

namespace shop\listeners\Shop\Product;

use shop\entities\Shop\events\ProductAppearedInStock;
use shop\entities\Shop\Product;
use shop\entities\User\User;
use shop\repositories\UserRepository;
use Yii;
use yii\base\ErrorHandler;

class ProductAppearedInStockListener
{
    private $users;
    private $mailer;
    private $errorHandler;

    public function __construct(UserRepository $users, ErrorHandler $errorHandler)
    {
        $this->users = $users;
        $this->errorHandler = $errorHandler;
    }

    public function handle(ProductAppearedInStock $event): void
    {
        if ($event->product->isActive()) {
            foreach ($this->users->getAllByProductInWishList($event->product->id) as $user) {
                if ($user->isActive()) {
                    try {
                        $this->sendEmailNotification($user, $event->product);
                    } catch (\Exception $e) {
                        $this->errorHandler->handleException($e);
                    }
                }
            }
        }
    }

    private function sendEmailNotification(User $user, Product $product): void
    {
        $sent = Yii::$app->mailer
            ->compose(
                ['html' => 'available-html', 'text' => 'available-text'],
                ['user' => $user, 'product' => $product]
            )
            ->setTo($user->email)
            ->setFrom(Yii::$app->params['supportEmail'])
            ->setSubject('Product is available')
            ->send();
        if (!$sent) {
            throw new \RuntimeException('Email sending error to ' . $user->email);
        }
    }
}