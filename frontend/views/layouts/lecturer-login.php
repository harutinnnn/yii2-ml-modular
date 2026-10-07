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
        <link href="/css/site.css" rel="stylesheet"/>
    </head>
    <body class="inner-v1">
    <?php $this->beginBody() ?>


    <?= $content ?>


    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
