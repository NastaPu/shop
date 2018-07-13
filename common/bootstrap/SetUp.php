<?php

namespace common\bootstrap;

use frontend\urls\CategoryUrlRule;
use shop\readModels\CategoryReadRepository;
use yii\base\BootstrapInterface;
use yii\caching\Cache;
use yii\di\Instance;
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

        $container->set(CategoryUrlRule::class, [], [
            Instance::of(CategoryReadRepository::class),
            Instance::of('cache'),
        ]);
    }
}
