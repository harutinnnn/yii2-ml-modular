<?php

use yii\helpers\Html;

?>
<header class="module-header">
    <a href="index.html">
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
        <?= Html::beginForm(['/' . Yii::$app->globalData->lang . '/logout'], 'post',['id'=>'logoutForm']) ?>

<!--        --><?php //= Html::submitButton('Աննա Մանուկյան · Ելք') ?>
        <a href="javascript:void(0)" onclick="$('#logoutForm').submit()">Աննա Մանուկյան · Ելք</a>

        <?= Html::endForm() ?>
    </span>
</header>

