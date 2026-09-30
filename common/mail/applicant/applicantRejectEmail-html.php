<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\User $user */
/** @var common\models\UserAdditionalData $additional */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
?>
<table id="email-content" role="presentation" align="center" width="100%" cellpadding="0" cellspacing="0"
       style="width:100%; max-width:600px; background-color:#faf8f3;">
     <tr>
        <td class="inset" style="padding:12px 40px 28px;">

            <p>
                Dear <?= $additional->first_name . ' ' . $additional->last_name ?>
            </p>
            <p>Your request was rejected!!!</p>


        </td>
    </tr>

</table>