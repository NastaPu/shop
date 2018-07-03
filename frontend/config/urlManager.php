<?php
return [
        'class' => 'yii\web\UrlManager',
        'hostInfo' => $params['frontendHostInfo'],
        'enablePrettyUrl' => true,
        'showScriptName' => false,
        'rules' => [

            '' => 'site/index',
            'site/confirm/<token:.+>' => 'site/confirm',
            'site/reset-password/<token:.+>' => 'site/reset-password',
            //'network/<authclient:.+>' => 'network/auth',
            'cabinet' => 'cabinet/default/index',
            'cabinet/network/attach/<authclient:.+>' => 'cabinet/network/attach',
            '<_a:about|contact|signup|login|logout>' => 'site/<_a>',
            '<_c:[\w\-]+>' => '<_c>/index',
           // '<_c:[\w\-]+>/<_a:[\w\-]+>/<token:[[\w]+\-|\_[\w]+]>' => '<_c>/<_a>',
            '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
            '<_c:[\w\-]+>/<_a:[\w-]+>' => '<_c>/<_a>',
            '<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_c>/<_a>',

           /* 'cabinet' => 'cabinet/default/index',
            'cabinet/<_c:[\w\-]+>' => 'cabinet/<_c>/index',
            'cabinet/<_c:[\w\-]+>/<id:\d+>' => 'cabinet/<_c>/view',
            'cabinet/<_c:[\w\-]+>/<_a:[\w-]+>' => 'cabinet/<_c>/<_a>',
            'cabinet/<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => 'cabinet/<_c>/<_a>',*/
        ],

];