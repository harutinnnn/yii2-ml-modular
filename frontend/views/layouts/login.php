<?php

/** @var \yii\web\View $this */

/** @var string $content */

use frontend\assets\AppAsset;
use yii\bootstrap5\Html;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <meta charset="utf-8"/>
        <meta content="width=device-width,initial-scale=1" name="viewport"/>
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <link href="/css/homepages.css" rel="stylesheet"/>
        <link rel="stylesheet" href="/css/review-changes.css">
        <link href="/css/site.css" rel="stylesheet"/>
    </head>
    <body class="o1 <?= Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'index' ? '' : 'inner-v1' ?>"
          id="top">
    <?php $this->beginBody() ?>

    <main class="login-shell">
        <?= $content ?>
    </main>


    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
