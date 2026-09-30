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

            <p>Dear <?= $additional->first_name ?? "" ?> <?= $additional->last_name ?? "" ?> your request has
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
        </td>
    </tr>

    <tr>
        <td class="inset" style="padding:12px 40px 28px;">
            <table role="presentation" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center" bgcolor="#9e1825" style="background-color:#9e1825; mso-padding-alt:17px 26px;">
                        <a href="<?= Yii::$app->request->hostInfo ?>/student/login"
                           style="display:inline-block; padding:17px 26px; border:1px solid #9e1825; color:#ffffff; font-family:Arial, sans-serif; font-size:14px; line-height:22px; font-weight:bold; text-decoration:none;">
                            Մուտք գործել
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>