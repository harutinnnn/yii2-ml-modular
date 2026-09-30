<?php

use yii\helpers\Html;

/** @var \yii\web\View $this view component instance */
/** @var \yii\mail\MessageInterface $message the message being composed */
/** @var string $content main view render result */

?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <meta name="color-scheme" content="light">
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title><?= Html::encode($this->title) ?></title>
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        table { border-collapse: collapse; }
        img { border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
        @media screen and (max-width: 620px) {
            .outer { padding: 16px 8px !important; }
            .inset { padding-left: 24px !important; padding-right: 24px !important; }
            .heading { font-size: 29px !important; line-height: 40px !important; }
            .brand-name { font-size: 11px !important; }
        }
    </style>
    <?php $this->head() ?>
</head>
<body style="margin:0; padding:0; width:100%; background-color:#f3efe8; color:#171717; font-family:Arial, sans-serif;">
    <div style="display:none; font-size:1px; line-height:1px; color:#f3efe8; max-height:0; max-width:0; opacity:0; overflow:hidden; mso-hide:all;">Հաստատեք Ձեր էլ. փոստի հասցեն՝ ՀՊՏՀ անձնական համակարգից օգտվելու համար։</div>
    <?php $this->beginBody() ?>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" bgcolor="#f3efe8" style="width:100%; background-color:#f3efe8;">
        <tr><td class="outer" align="center" style="padding:40px 16px;">
                <!--[if mso]><table role="presentation" width="600" align="center"><tr><td><![endif]-->
                <!-- Email header -->
                <table id="email-header" role="presentation" align="center" width="100%" cellpadding="0" cellspacing="0" style="width:100%; max-width:600px; background-color:#faf8f3;">
                    <tr><td height="5" bgcolor="#9e1825" style="height:5px; font-size:0; line-height:0;">&nbsp;</td></tr>
                    <tr><td class="inset" style="padding:28px 40px; border-bottom:1px solid #ddd5c9;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td width="112" valign="middle" style="width:112px; color:#9e1825; font-family:Georgia, 'Times New Roman', serif; font-size:30px; font-weight:bold;">ՀՊՏՀ</td>
                                    <td class="brand-name" valign="middle" style="border-left:1px solid #ddd5c9; padding-left:18px; font-size:12px; line-height:20px; color:#514943;">Հայաստանի պետական<br>տնտեսագիտական համալսարան</td>
                                </tr>
                            </table>
                        </td></tr>
                    <tr>
                        <td bgcolor="#3d0f17" style="background-color:#3d0f17;">
                            <img src="<?= Yii::$app->request->hostInfo ?>/images/home/campus-night.jpg" width="600" alt="Հայաստանի պետական տնտեսագիտական համալսարան"
                                 style="display:block; width:100%; max-width:600px; height:auto; color:#ffffff; font-size:14px;">
                        </td>
                    </tr>
                </table>
                <!-- Email content -->
                <?= $content ?>

                <table id="email-after-content" role="presentation" align="center" width="100%" cellpadding="0" cellspacing="0"
                       style="width:100%; max-width:600px; background-color:#faf8f3;">
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
                <!-- Email footer -->
                <table id="email-footer" role="presentation" align="center" width="100%" cellpadding="0" cellspacing="0" style="width:100%; max-width:600px; background-color:#f3efe8;">
                    <tr><td class="inset" bgcolor="#171414" style="padding:28px 40px; background-color:#171414; color:#ffffff;">
                            <p style="margin:0 0 10px; font-size:13px; line-height:22px; font-weight:bold;">Հայաստանի պետական տնտեսագիտական համալսարան</p>
                            <p style="margin:0 0 14px; font-size:12px; line-height:22px; color:#c9bec0;">ՀՀ, Երևան 0025, Նալբանդյան 128</p>
                            <a href="mailto:info@asue.am" style="font-size:12px; line-height:22px; color:#edc5ca; text-decoration:underline;">info@asue.am</a>
                            <span style="padding:0 10px; color:#766769;">&middot;</span>
                            <a href="tel:+37410593483" style="font-size:12px; line-height:22px; color:#edc5ca; text-decoration:none;">+374 10 593 483</a>
                        </td></tr>
                    <tr><td align="center" style="padding:20px 0 0;">
                            <p style="margin:0; font-size:11px; line-height:20px; color:#766d65;">ՀՊՏՀ &middot; Անձնական համակարգ<br>Այս նամակն ուղարկվել է ինքնաշխատ եղանակով։</p>
                        </td></tr>
                </table>
                <!--[if mso]></td></tr></table><![endif]-->
            </td></tr>
    </table>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
