<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $user */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>

<table id="email-content" role="presentation" align="center" width="100%" cellpadding="0" cellspacing="0"
       style="width:100%; max-width:600px; background-color:#faf8f3;">
    <tr>
        <td class="inset" bgcolor="#7f1424" style="padding:30px 40px 34px; background-color:#7f1424; color:#ffffff;">
            <p style="margin:0 0 12px; font-size:11px; line-height:18px; letter-spacing:2px; font-weight:bold; color:#edc5ca;">
                ԱՆՁՆԱԿԱՆ ՀԱՄԱԿԱՐԳ</p>
            <h1 class="heading"
                style="margin:0; font-family:Georgia, 'Times New Roman', serif; font-size:36px; line-height:48px; font-weight:normal; color:#ffffff;">
                Բարի գալուստ<br>ՀՊՏՀ թվային միջավայր</h1>
        </td>
    </tr>

    <tr>
        <td class="inset" style="padding:12px 40px 28px;">

            <p>Hello <?= Html::encode($user->email) ?>,</p>

        </td>
    </tr>

    <tr>
        <td class="inset" style="padding:12px 40px 28px;">
            <p>Follow the link below to reset your password:</p>
        </td>
    </tr>

    <tr>
        <td style="padding:0 40px 36px;">
            <a href="<?= $resetLink ?>"
               style="display:inline-block; padding:17px 26px; border:1px solid #9e1825; color:#ffffff;background: #9e1825; font-family:Arial, sans-serif; font-size:14px; line-height:22px; font-weight:bold; text-decoration:none;">
                Reset password
            </a>
        </td>
    </tr>
</table>
