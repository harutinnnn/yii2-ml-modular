<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $user */
/** @var common\models\UserAdditionalData $userAdditionalData */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
?>
<div class="verify-email">
    <p>Hello <?= Html::encode($user->email) ?>,</p>

    <p>Dear <?= $userAdditionalData->first_name ?? "" ?> <?= $userAdditionalData->last_name ?? "" ?> your request has
        been approved</p>
    <p>
        Congratulations you are now student in ASUE
    </p>
    <p>
        You can log in website as student by this credentials
    </p>
    <p>
        Login: <strong><?= $user->email ?? "" ?></strong>
    </p>
    <p>
        Password: <strong><?= $pass ?? "" ?></strong>
    </p>
</div>
