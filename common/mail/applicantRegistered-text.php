<?php

/** @var yii\web\View $this */
/** @var common\models\User $user */
/** @var common\models\UserAdditionalData $additional */


?>
New applicant request
Email: <?php $user->email ?>

Name: <?= $additional->first_name ?? "" ?> <?= $additional->last_name ?? "" ?>
