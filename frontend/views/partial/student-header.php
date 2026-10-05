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
        <a href="#profile">Անձնական տվյալներ</a>
        <a href="#card">Ուսանողական տոմս</a>
        <a href="#requests">Դիմումներ</a>
        <a href="communications.html">Նամակագրություն</a>
        <a href="notifications.html">Ծանուցումներ</a>
        <a href="#portfolio">Պորտֆոլիո</a>
    </nav>
    <span class="module-user">
        <?= Html::beginForm(['/' . Yii::$app->globalData->lang . '/logout'], 'post', ['id' => 'logoutForm']) ?>


        <?= $userAdditional->first_name ?> <?= $userAdditional->first_name ?> ·
        <a href="javascript:void(0)" onclick="$('#logoutForm').submit()">
        <?= \common\components\I18n::translate('sign_out') ?>
        </a>

        <?= Html::endForm() ?>
    </span>
</header>

