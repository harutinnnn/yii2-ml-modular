<?php

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var Exception $exception */

use yii\helpers\Html;

$isNotFound = $exception instanceof \yii\web\HttpException && $exception->statusCode === 404;
$this->title = $isNotFound ? Yii::t('app', 'Page not found') : $name;
$this->params['hideMainHeader'] = true;
$homeUrl = ['site/index', 'language' => Yii::$app->globalData->lang];
?>
<section class="inner-hero" aria-labelledby="error-title">
    <div class="wrap">
        <div class="breadcrumbs">
            <?= Html::a(Html::encode(Yii::t('app', 'Home')), $homeUrl) ?>
            / <?= Html::encode($this->title) ?>
        </div>
        <div class="eyebrow"><?= $isNotFound ? '404' : Html::encode(Yii::t('app', 'Error')) ?></div>
        <h1 id="error-title"><?= Html::encode($this->title) ?></h1>
        <p class="lead">
            <?= $isNotFound
                ? Html::encode(Yii::t('app', 'The page you are looking for may have moved, been removed, or the address may be incorrect.'))
                : nl2br(Html::encode($message)) ?>
        </p>
    </div>
</section>
<section class="inner-section">
    <div class="wrap">
        <div class="eyebrow red"><?= Html::encode(Yii::t('app', 'Continue exploring')) ?></div>
        <h2 class="display-inner"><?= Html::encode(Yii::t('app', 'Let’s get you back on track.')) ?></h2>
        <p class="body-copy"><?= Html::encode(Yii::t('app', 'Return to the homepage or use the search in the header to find what you need.')) ?></p>
        <?= Html::a(Html::encode(Yii::t('app', 'Back to homepage')) . ' &rarr;', $homeUrl, ['class' => 'btn-red']) ?>
    </div>
</section>
