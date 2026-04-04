<?php

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'layout' => 'main',
    'controllerMap' => [
        'elfinder' => [
            'class' => \mihaildev\elfinder\Controller::class,
            'access' => ['@'],
            'disabledCommands' => ['netmount'],
            'managerOptions' => [
                'noConflict' => true,
            ],
            'roots' => [
                [
                    'baseUrl' => '/uploads',
                    'basePath' => dirname(__DIR__, 2) . '/frontend/web/uploads',
                    'path' => 'content',
                    'name' => 'Content Images',
                ],
            ],
        ],
    ],
    'modules' => [
        'content' => [
            'class' => \backend\modules\content\Module::class,
        ],
        'email-content' => [
            'class' => \backend\modules\emailcontent\Module::class,
        ],
        'frontend-language' => [
            'class' => \backend\modules\frontendlanguage\Module::class,
        ],
        'menu' => [
            'class' => \backend\modules\menu\Module::class,
        ],
        'language' => [
            'class' => \backend\modules\language\Module::class,
        ],
        'section' => [
            'class' => \backend\modules\section\Module::class,
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'baseUrl' => '/admin',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'assetManager' => [
            'basePath' => dirname(__DIR__) . '/web/assets',
            'baseUrl' => '/admin/assets',
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => [
                        '@app/views/adminlte',
                        '@vendor/hail812/yii2-adminlte3/src/views',
                    ],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
    ],
    'params' => $params,
];
