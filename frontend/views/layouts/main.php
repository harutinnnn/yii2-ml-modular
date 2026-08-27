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
    </head>
    <body class="o1 <?= Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'index' ? '' : 'inner-v1' ?>"
          id="top">
    <?php $this->beginBody() ?>

    <?= $this->render('//partial/header') ?>
    <?= $this->render('//partial/menu-popup') ?>
    <?= $this->render('//partial/search-popup') ?>
    <main>

        <?php if (Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'index'): ?>
            <?= $this->render('//partial/home-header') ?>
        <?php else: ?>
            <?= $this->render('//partial/main-header') ?>
        <?php endif; ?>

        <?= $content ?>
    </main>


    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
