<?php

use common\components\I18n;

$menuObj = $this->context->pageData['menuObj'];
$parentMenuObj = $this->context->pageData['parentMenuObj'];
$preParentMenuObj = $this->context->pageData['preParentMenuObj'];

$headerImage = $menuObj['header_image'] ?? '';
if ((!$headerImage || !strlen(trim($headerImage))) && isset($this->params['headerImage'])) {
    $headerImage = $this->params['headerImage'];
}

$headerSubNav = $menuObj->headerSubNav ?? [];

?>

<section class="inner-hero" style="--hero:url('<?= $headerImage ?>')">
    <div class="wrap">
        <div class="breadcrumbs">
            <a href="/<?= Yii::$app->globalData->lang ?>"><?= I18n::translate('header_bread_main') ?></a>
            / <?= $menuObj?->getTranslation(Yii::$app->globalData->lang)->title ?>
        </div>
        <div class="eyebrow">
            <?php if (isset($preParentMenuObj) && !empty($preParentMenuObj)) : ?>
                <?= $preParentMenuObj?->getTranslation(Yii::$app->globalData->lang)->title ?? '' ?>
            <?php else: ?>
                <?= $parentMenuObj?->getTranslation(Yii::$app->globalData->lang)->title ?? '' ?>
            <?php endif; ?>
        </div>
        <h1><?= $menuObj?->getTranslation(Yii::$app->globalData->lang)->title ?? "" ?></h1>
        <p class="lead">
            <?= strip_tags($menuObj?->getTranslation(Yii::$app->globalData->lang)->description ?? "") ?>
        </p>
    </div>
</section>
<?php ?>

<?php if (!empty($headerSubNav)): ?>
    <nav class="inner-subnav">
        <div class="wrap">
            <?php foreach ($headerSubNav as $headerSubNavItem): ?>
                <a href="#mission"><?= \common\models\TranslationModel::getTranslation($headerSubNavItem->sideMenu, Yii::$app->globalData->lang) ?></a>
            <?php endforeach; ?>
        </div>
    </nav>
<?php endif; ?>
