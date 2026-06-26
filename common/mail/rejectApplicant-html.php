<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $user */
/** @var common\models\UserAdditionalData $userAdditionalData */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
?>
<div class="verify-email">
    <p>Hello <?= Html::encode($user->email) ?>,</p>

    <p>Dear <?= $userAdditionalData->first_name ?? "" ?> <?= $userAdditionalData->last_name ?? "" ?> your request has been rejected</p>
</div>
