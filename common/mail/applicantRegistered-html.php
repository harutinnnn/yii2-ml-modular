<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $user */
/** @var common\models\UserAdditionalData $additional */

?>
<div class="verify-email">
    <h1>New applicant request</h1>
    <p>Email: <?= Html::encode($user->email) ?>,</p>

    <p>Name: <?= $additional->first_name ?? "" ?> <?= $additional->last_name ?? "" ?></p>
</div>
