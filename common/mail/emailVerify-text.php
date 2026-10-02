<?php

/** @var yii\web\View $this */
/** @var common\models\User $user */

$verifyLink = Yii::$app->request->hostInfo . '/verify-email?token=' . $user->verification_token;
?>
Dear <?= $additional->first_name ?? "" ?> <?= $additional->last_name ?? "" ?> your request has

Follow the link below to verify your email:

Login: <?= $user->email ?? "" ?>
Password: <?= $pass ?? "" ?>

<?= $verifyLink ?>
