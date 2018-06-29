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
            'network/<authclient:[\w]+>' => 'network/auth',
            '<_a:about|contact|signup|login|logout>' => 'site/<_a>',
            '<_c:[\w\-]+>' => '<_c>/index',
           // '<_c:[\w\-]+>/<_a:[\w\-]+>/<token:[[\w]+\-|\_[\w]+]>' => '<_c>/<_a>',
            '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',

            //'<_a:confirm>'=>'site/<_a>'
            //'<_c:[\w\-]+>/<_a:confirm>?<\w+>' => '<_c:[\w\-]+>/<_a:confirm>/index.php>',
            //'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
            '<_c:[\w\-]+>/<_a:[\w-]+>' => '<_c>/<_a>',
            '<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_c>/<_a>',
        ],

];