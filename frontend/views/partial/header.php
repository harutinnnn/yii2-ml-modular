<?php

use common\components\I18n;
use yii\helpers\Html;


$user = Yii::$app->user->identity;
$userAdditional = $user?->additional ?? null;


?>
<header class="site-header">
    <a class="brand" href="/<?= Yii::$app->globalData->lang ?>">
        <img alt="ՀՊՏՀ" src="/images/logo_am.svg">
    </a>

    <div class="header-actions">

        <?php if (Yii::$app->user->isGuest): ?>
            <a class="header-link" href="/<?= Yii::$app->globalData->lang ?>/login">
                <?= I18n::translate('login') ?>
            </a>
        <?php else: ?>

            <span class="module-user">
            <?= Html::beginForm(['/' . Yii::$app->globalData->lang . '/logout'], 'post', ['id' => 'logoutForm']) ?>

            <a href="javascript:void(0)" onclick="$('#logoutForm').submit()">
                <?= I18n::translate('sign_out') ?>
            </a>

            <?= Html::endForm() ?>
        </span>
        <?php endif; ?>

        <a class="header-link advanced-search" href="/<?= Yii::$app->globalData->lang ?>/search">
            <?= I18n::translate('advanced_search') ?>
        </a>
        <button class="header-link" data-search-open=""><?= I18n::translate('search') ?></button>
        <div class="lang">HY · EN · RU</div>
        <button aria-label="Բացել մենյուն" class="burger" data-menu-open=""><i></i></button>
    </div>
</header>