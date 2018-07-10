<?php

namespace frontend\controllers\cabinet;

use shop\services\auth\NetworkService;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;
use yii\authclient\AuthAction;
use yii\web\Controller;
use yii\helpers\Url;
use Yii;

class NetworkController extends Controller
{
    public $layout = 'cabinet';

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
            'attach' => [
                'class' => AuthAction::class,
                'successCallback' => [$this, 'onAuthSuccess'],
                'successUrl' => Url::to(['cabinet/default/index']),
            ],
        ];
    }

    public function onAuthSuccess(ClientInterface $client):void
    {
        $network = $client->getId();
        $attributes = $client->getUserAttributes();
        $identity = ArrayHelper::getValue($attributes, 'id');

        try {
            $this->networkService->attach(Yii::$app->user->id, $network, $identity);
            Yii::$app->session->setFlash('success', 'Network is successfully attached ');
            //Yii::$app->user->login($user, Yii::$app->params['user.rememberMeDuration']);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            Yii::$app->errorHandler->logException($e);
        }
    }
}