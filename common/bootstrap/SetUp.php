<?php

namespace common\bootstrap;

use DrewM\MailChimp\MailChimp;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use shop\cart\Cart;
use shop\cart\cost\calculator\DynamicCost;
use shop\cart\cost\calculator\SimpleCost;
use shop\cart\storage\HybridStorage;
use shop\dispatcher\EventDispatcher;
use shop\dispatcher\SimpleEventDispatcher;
use shop\listeners\UserSignupRequestedListener;
use shop\services\auth\events\UserSignupRequested;
use shop\services\newsletter\Newsletter;
use shop\services\sms\LoggedSender;
use shop\services\sms\SmsRu;
use shop\services\sms\SmsSender;
use shop\services\yandex\ShopInfo;
use shop\services\yandex\YandexMarket;
use yii\caching\Cache;
use yii\di\Container;
use yii\rbac\ManagerInterface;
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

        $container->setSingleton(Cache::class, function () use ($app) {
            return $app->cache;
        });

        $container->setSingleton(Client::class, function () {
            return ClientBuilder::create()->build();
        });

        $container->setSingleton(Cart::class, function () use ($app) {
            return new Cart(
                new HybridStorage($app->get('user'), 'cart', 3600 * 24, $app->db),
                new DynamicCost(new SimpleCost())
            );
        });

        $container->setSingleton(ManagerInterface::class, function () use ($app) {
            return $app->authManager;
        });

        $container->setSingleton(YandexMarket::class, [], [
            new ShopInfo($app->name, $app->name, $app->params['frontendHostInfo']),
        ]);

        $container->setSingleton(Newsletter::class, function () use ($app) {
            return new MailChimp(
                new \DrewM\MailChimp\MailChimp($app->params['mailChimpKey']),
                $app->params['mailChimpListId']
            );
        });

        $container->setSingleton(SmsSender::class, function () use ($app) {
            return new LoggedSender(
                new SmsRu($app->params['smsRuKey']), \Yii::getLogger()
            );
        });

        $container->setSingleton(EventDispatcher::class, function (Container $container) {
            return new SimpleEventDispatcher([
                UserSignUpRequested::class => [
                    [$container->get(UserSignupRequestedListener::class), 'handle'],
                ],
            ]);
        });
    }

}
