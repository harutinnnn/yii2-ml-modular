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
        <link href="/css/spec-modules.css" rel="stylesheet"/>
        <link rel="stylesheet" href="/css/review-changes.css">
        <link href="/css/site.css" rel="stylesheet"/>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.4.0/socket.io.js"
                integrity="sha512-Y8KodDCDqst1e8z0EGKiqEQq3T8NszmgW2HvsC6+tlNw7kxYxHTLl5Iw/gqZj/6qhZdBt+jYyOsybgSAiB9OOA=="
                crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script type="text/javascript">
            const lang = '<?= Yii::$app->globalData->lang ?>'
            const userId = <?= \yii\helpers\Json::htmlEncode(
                    Yii::$app->user->id
            ) ?>;
        </script>
    </head>
    <body class="module-body" id="top">
    <?php $this->beginBody() ?>

    <?= $this->render('//student/student-header') ?>

    <main class="login-shell">
        <?= $content ?>
    </main>

    <footer class="module-footer">Հիմնական տվյալները կարող է փոփոխել միայն լիազորված ադմինիստրատորը։</footer>
    <script src="/js/spec-modules.js"></script>
    <script src="/js/socket.js"></script>


    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
