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
        <a href="#research">Գիտական նյութեր</a>
        <a href="#requests">Տեղեկանքներ</a>
        <a href="communications.html">Նամակագրություն</a>
        <a href="notifications.html">Ծանուցումներ</a>
    </nav>
    <span class="module-user">
        <?= Html::beginForm(['/' . Yii::$app->globalData->lang . '/logout'], 'post', ['id' => 'logoutForm']) ?>

        <a href="javascript:void(0)" onclick="$('#logoutForm').submit()">
            <?= $userAdditional->first_name ?> <?= $userAdditional->first_name ?> · <?= \common\components\I18n::translate('sign_out') ?>
        </a>

        <?= Html::endForm() ?>
    </span>
</header>