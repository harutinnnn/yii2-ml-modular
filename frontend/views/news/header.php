<?php

use common\components\I18n;

$menuObj = $this->context->pageData['menuObj'];
$parentMenuObj = $this->context->pageData['parentMenuObj'];
$preParentMenuObj = $this->context->pageData['preParentMenuObj'];

$headerImage = $menuObj['header_image'] ?? '';
if ((!$headerImage || !strlen(trim($headerImage))) && isset($this->params['headerImage'])) {
    $headerImage = $this->params['headerImage'];
}
?>

<section class="inner-hero" style="--hero:url('<?= $headerImage ?>')">
    <div class="wrap">
        <div class="breadcrumbs">
            <a href="/<?= Yii::$app->globalData->lang ?>"><?= I18n::translate('header_bread_main') ?></a>
            / <?= $menuObj?->getTranslation(Yii::$app->globalData->lang)->title ?>
        </div>
        <div class="eyebrow">
            <?= $parentMenuObj?->getTranslation(Yii::$app->globalData->lang)->title ?? '' ?>
        </div>
        <h1><?= $menuObj?->getTranslation(Yii::$app->globalData->lang)->title ?? "" ?></h1>
        <p class="lead">
            <?= strip_tags($menuObj?->getTranslation(Yii::$app->globalData->lang)->description ?? "") ?>
        </p>
    </div>
</section>

<nav class="inner-subnav">
    <div class="wrap">
        <a href="#latest">Նորություններ</a>
        <a href="/<?= Yii::$app->globalData->lang ?>/announcements">Հայտարարություններ</a>
        <a href="/<?= Yii::$app->globalData->lang ?>/events">Միջոցառումներ</a>
        <a href="/<?= Yii::$app->globalData->lang ?>/media">Մեդիա</a>
    </div>
</nav>