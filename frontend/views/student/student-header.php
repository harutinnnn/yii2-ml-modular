<?php

use yii\helpers\Html;

$user = Yii::$app->user->identity;
$userAdditional = $user?->additional ?? null;

?>
<header class="module-header">
    <a href="/<?= Yii::$app->globalData->lang ?>">
        <img src="/images/logo_am.svg" alt="ՀՊՏՀ">
    </a>
    <nav>
        <?php if (isset(Yii::$app->globalData->settings['headerMenuLinks']) && Yii::$app->globalData->settings['headerMenuLinks']): ?>
            <a href="#profile">Անձնական տվյալներ</a>
            <a href="#card">Ուսանողական տոմս</a>
            <a href="#requests">Դիմումներ</a>
            <a href="/<?= Yii::$app->globalData->lang ?>/student/communications">Նամակագրություն</a>
            <a href="/<?= Yii::$app->globalData->lang ?>/student/notifications">Ծանուցումներ</a>
            <a href="#portfolio">Պորտֆոլիո</a>
        <?php else: ?>
            <a href="/<?= Yii::$app->globalData->lang ?>/student/student-personal-dashboard#profile">Անձնական տվյալներ</a>
            <a href="/<?= Yii::$app->globalData->lang ?>/student/student-personal-dashboard#card">Ուսանողական տոմս</a>
            <a href="/<?= Yii::$app->globalData->lang ?>/student/student-personal-dashboard#requests">Դիմումներ</a>
            <a href="/<?= Yii::$app->globalData->lang ?>/student/communications">Նամակագրություն</a>
            <a href="/<?= Yii::$app->globalData->lang ?>/student/notifications">Ծանուցումներ</a>
            <a href="/<?= Yii::$app->globalData->lang ?>/student/student-personal-dashboard#portfolio">Պորտֆոլիո</a>
        <?php endif; ?>
    </nav>
    <span class="module-user">
        <?= Html::beginForm(['/' . Yii::$app->globalData->lang . '/logout'], 'post', ['id' => 'logoutForm']) ?>


        <?= $userAdditional->first_name ?? '-' ?> <?= $userAdditional->last_name ?? '-' ?> ·
        <a href="javascript:void(0)" onclick="$('#logoutForm').submit()">
        <?= \common\components\I18n::translate('sign_out') ?>
        </a>

        <?= Html::endForm() ?>
    </span>
</header>

