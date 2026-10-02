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
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <link href="/css/spec-modules.css" rel="stylesheet"/>
    </head>
    <body class="module-body" id="top">
    <?php $this->beginBody() ?>

    <?= $this->render('//partial/teacher-header') ?>

    <main class="login-shell">
        <?= $content ?>
    </main>

    <footer class="module-footer">Հիմնական տվյալները կարող է փոփոխել միայն լիազորված ադմինիստրատորը։</footer>
    <script src="/js/spec-modules.js"></script>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
