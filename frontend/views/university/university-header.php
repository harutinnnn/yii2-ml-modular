<?php

use common\components\I18n;

$bread = Yii::$app->globalData->pageData['bread'] ?? '';
$preTitle = Yii::$app->globalData->pageData['preTitle'] ?? '';
$pageTitle = Yii::$app->globalData->pageData['pageTitle'] ?? '';
$afterTitle = Yii::$app->globalData->pageData['afterTitle'] ?? '';
$headerImage = Yii::$app->globalData->pageData['headerImage'] ?? '';

?>

<section class="inner-hero" style="--hero:url('<?= $headerImage ?>')">
    <div class="wrap">
        <div class="breadcrumbs">
            <a href="/<?= Yii::$app->globalData->lang ?>">
                <?= I18n::translate('header_bread_main') ?></a>
            / <?= $bread ?>
        </div>
        <div class="eyebrow">
            <?= $preTitle ?>
        </div>
        <h1><?= $pageTitle ?></h1>
        <p class="lead">
            <?= $afterTitle ?>
        </p>
    </div>
</section>
