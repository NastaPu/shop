<?php

namespace frontend\controllers;

use common\auth\Identity;
use shop\services\auth\NetworkService;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;
use yii\authclient\AuthAction;
use yii\web\Controller;
use Yii;

class NetworkController extends Controller
{
    private $networkService;

    public function __construct(
        $id,
        $module,
        NetworkService $networkService,
        $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->networkService= $networkService;
    }

    public function actions()
    {
        return [
            'auth' => [
                'class' => AuthAction::class,
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function onAuthSuccess(ClientInterface $client):void
    {
        $network = $client->getId();
        $attributes = $client->getUserAttributes();
        $identity = ArrayHelper::getValue($attributes, 'id');

        try {
            $user = $this->networkService->auth($network, $identity);
            Yii::$app->user->login(new Identity($user), Yii::$app->params['user.rememberMeDuration']);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            Yii::$app->errorHandler->logException($e);
        }
    }
}