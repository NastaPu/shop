<?php
return [
        'class' => 'yii\web\UrlManager',
        'hostInfo' => $params['frontendHostInfo'],
        'enablePrettyUrl' => true,
        'showScriptName' => false,
        'rules' => [

            'catalog' => 'shop/catalog/index',
            '' => 'site/index',
            'site/confirm/<token:.+>' => 'site/confirm',
            'site/reset-password/<token:.+>' => 'site/reset-password',
            'cabinet' => 'cabinet/default/index',
            'cabinet/network/attach/<authclient:.+>' => 'cabinet/network/attach',
            '<_a:about|contact|signup|login|logout>' => 'site/<_a>',
            '<_c:[\w\-]+>' => '<_c>/index',
            '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
            '<_c:[\w\-]+>/<_a:[\w-]+>' => '<_c>/<_a>',
            '<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_c>/<_a>',

            'catalog/product/<id:\d+>' => 'shop/catalog/product',

            ['pattern' => 'yandex-market', 'route' => 'market/index', 'suffix' => '.xml'],

        ],

];