<?php

namespace common\bootstrap;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use shop\cart\Cart;
use shop\cart\cost\calculator\DynamicCost;
use shop\cart\cost\calculator\SimpleCost;
use shop\cart\storage\CookieStorage;
use shop\cart\storage\SessionStorage;
use yii\base\BootstrapInterface;
use yii\mail\MailerInterface;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app):void//вызывается в начале загрузки приложения
    {
        $container = \Yii::$container;
        $container->setSingleton(MailerInterface::class, function () use ($app) {
            return $app->mailer;
        });
       // $container->setSingleton(ContactService::class,[],[
       //     $app->params['adminEmail'],
       // ]);

        $container->setSingleton('cache', function () use ($app) {
            return $app->cache;
        });

       /* $container->set(CategoryUrlRule::class, [], [
            Instance::of(CategoryReadRepository::class),
            Instance::of('cache'),
        ]);*/

        $container->setSingleton(Client::class, function () {
            return ClientBuilder::create()->build();
       });

        $container->setSingleton(Cart::class, function () {
            return new Cart(
                new CookieStorage('cart', 3600),
                new DynamicCost(new SimpleCost())
            );
        });
    }
}
