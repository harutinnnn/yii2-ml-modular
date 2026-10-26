<?php

use common\components\I18n;
use common\components\UrlHelper;
use common\components\UserRoles;
use yii\helpers\Html;


$user = Yii::$app->user->identity;
$userAdditional = $user?->additional ?? null;


?>
<header class="site-header">
    <a class="brand" href="/<?= Yii::$app->globalData->lang ?>">
        <img alt="ՀՊՏՀ" src="/images/logo_am.svg">
    </a>

    <div class="header-actions">

        <?php if (Yii::$app->user->isGuest): ?>
            <a class="header-link" href="/<?= Yii::$app->globalData->lang ?>/login">
                <?= I18n::translate('login') ?>
            </a>
        <?php else: ?>

            <?php

            $userDashboard = '';
            $usertype = Yii::$app->session->get('userType');

            if ($usertype == UserRoles::STUDENT) {

                $userDashboard = '/student/student-personal-dashboard';

            } else if ($usertype == UserRoles::TEACHER) {

                $userDashboard = '/lecturer/personal-dashboard';

            } else if ($usertype == UserRoles::ADMINISTRATIVE_STAFF) {

                $userDashboard = '/administrative/personal-dashboard';

            }

            ?>
            <a class="header-link" href="/<?= Yii::$app->globalData->lang ?><?= $userDashboard ?>">
                <?= $userAdditional->first_name ?? '-' ?> <?= $userAdditional->last_name ?? '-' ?>
            </a>
        <?php endif; ?>

        <a class="header-link advanced-search" href="/<?= Yii::$app->globalData->lang ?>/search">
            <?= I18n::translate('advanced_search') ?>
        </a>
        <button class="header-link" data-search-open=""><?= I18n::translate('search') ?></button>
        <div class="lang">

            <?php

            $currentUrl = Yii::$app->request->url;


            $langList = [];
            foreach (Yii::$app->globalData->languages as $langCode => $langTitle) {

                $newUrl = UrlHelper::findAndChangeUrlLang($currentUrl, $langCode);

                $isActive = '';
                if ($langCode == Yii::$app->globalData->lang) {
                    $langList[$langCode] = '<span class="active">' . strtoupper($langCode) . '</span>';
                } else {
                    $langList[$langCode] = '<a href="' . $newUrl . '">' . strtoupper($langCode) . '</a>';
                }
            }
            ?>
            <?= implode(' · ', $langList) ?>
        </div>
        <button aria-label="Բացել մենյուն" class="burger" data-menu-open=""><i></i></button>
    </div>
</header>