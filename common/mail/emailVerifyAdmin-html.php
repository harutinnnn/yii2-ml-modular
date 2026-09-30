<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $user */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
?>
<table id="email-content" role="presentation" align="center" width="100%" cellpadding="0" cellspacing="0"
       style="width:100%; max-width:600px; background-color:#faf8f3;">
    <tr>
        <td bgcolor="#3d0f17" style="background-color:#3d0f17;">
            <img src="/images/home/campus-night.jpg" width="600" alt="Հայաստանի պետական տնտեսագիտական համալսարան"
                 style="display:block; width:100%; max-width:600px; height:auto; color:#ffffff; font-size:14px;">
        </td>
    </tr>
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
        <td class="inset" style="padding:36px 40px 16px;">
            <h2 style="margin:0 0 18px; font-family:Georgia, 'Times New Roman', serif; font-size:24px; line-height:34px; font-weight:normal;">
                Հաստատեք Ձեր էլ. փոստը</h2>
            <p style="margin:0 0 14px; font-size:15px; line-height:26px;">Բարև Ձեզ,</p>
            <p style="margin:0; font-size:15px; line-height:27px; color:#665e57;">Շնորհակալություն գրանցվելու համար։ Ձեր
                գրանցումն ավարտելու և անձնական համակարգից օգտվելու համար խնդրում ենք հաստատել Ձեր էլ. փոստի հասցեն։</p>
        </td>
    </tr>
    <tr>
        <td class="inset" style="padding:12px 40px 28px;">
            <table role="presentation" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center" bgcolor="#9e1825" style="background-color:#9e1825; mso-padding-alt:17px 26px;">
                        <a href="{{verification_url}}"
                           style="display:inline-block; padding:17px 26px; border:1px solid #9e1825; color:#ffffff; font-family:Arial, sans-serif; font-size:14px; line-height:22px; font-weight:bold; text-decoration:none;">Հաստատել
                            էլ. փոստը&nbsp; &rarr;</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="inset" style="padding:0 40px 30px;">
            <p style="margin:0 0 8px; font-size:12px; line-height:21px; color:#766d65;">Եթե կոճակը չի աշխատում, պատճենեք
                այս հղումը և բացեք այն Ձեր դիտարկիչում՝</p>
            <a href="{{verification_url}}"
               style="font-size:12px; line-height:22px; color:#9e1825; text-decoration:underline; word-break:break-all; overflow-wrap:anywhere;">{{verification_url}}</a>
        </td>
    </tr>
    <tr>
        <td class="inset" style="padding:0 40px 36px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td bgcolor="#f3efe8"
                        style="padding:18px 20px; border-left:3px solid #9e1825; font-size:12px; line-height:22px; color:#665e57;">
                        Եթե Դուք չեք գրանցվել մեր համակարգում, կարող եք անտեսել այս նամակը։
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<div class="verify-email">
    <p>Hello <?= Html::encode($user->email) ?>,</p>
    <p>Your password: <?= $user->password ?></p>

    <p>Follow the link below to verify your email:</p>

    <p><?= Html::a(Html::encode($verifyLink), $verifyLink) ?></p>
</div>
