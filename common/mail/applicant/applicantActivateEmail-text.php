<?php

/** @var yii\web\View $this */
/** @var common\models\User $user */
/** @var common\models\UserAdditionalData $additional */

?>
Dear <?= $additional->first_name ?? "" ?> <?= $additional->last_name ?? "" ?> your request has been approved

Congratulations you are now student in ASUE