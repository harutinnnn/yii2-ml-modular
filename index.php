<?php

declare(strict_types=1);

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/common/config/bootstrap.php';

$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$isBackendRequest = preg_match('#^/admin(?:/|$)#', $requestPath) === 1;
$appName = $isBackendRequest ? 'backend' : 'frontend';
$webPath = __DIR__ . '/' . $appName . '/web';
$baseUrl = $isBackendRequest ? '/admin' : '';

Yii::setAlias('@webroot', $webPath);
Yii::setAlias('@web', $baseUrl);

require __DIR__ . '/' . $appName . '/config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/common/config/main.php',
    require __DIR__ . '/common/config/main-local.php',
    require __DIR__ . '/' . $appName . '/config/main.php',
    require __DIR__ . '/' . $appName . '/config/main-local.php'
);

(new yii\web\Application($config))->run();
