<?php

use yii\helpers\Html;
use yii\helpers\Url;

$username = Yii::$app->user->isGuest ? 'Guest' : Yii::$app->user->identity->username;
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?= Url::to(['/site/index']) ?>" class="brand-link">
        <img src="<?= $assetDir ?>/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light"><?= Html::encode(Yii::$app->name) ?></span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= $assetDir ?>/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="<?= Url::to(['/site/index']) ?>" class="d-block"><?= Html::encode($username) ?></a>
            </div>
        </div>

        <nav class="mt-2">
            <?= \hail812\adminlte\widgets\Menu::widget([
                'items' => [
                    ['label' => 'Main Navigation', 'header' => true],
                    ['label' => 'Dashboard', 'icon' => 'tachometer-alt', 'url' => ['/site/index']],
                    ['label' => 'Content', 'header' => true],
                    ['label' => 'Content Items', 'icon' => 'copy', 'url' => ['/content/content/index']],
                    ['label' => 'Languages', 'icon' => 'language', 'url' => ['/language/language/index']],
                    ['label' => 'Yii Tools', 'header' => true, 'visible' => YII_ENV_DEV],
                    ['label' => 'Gii', 'icon' => 'file-code', 'url' => ['/gii'], 'target' => '_blank', 'visible' => YII_ENV_DEV],
                    ['label' => 'Debug', 'icon' => 'bug', 'url' => ['/debug'], 'target' => '_blank', 'visible' => YII_ENV_DEV],
                    ['label' => 'Frontend', 'header' => true],
                    ['label' => 'Open Site', 'icon' => 'globe', 'url' => ['/'], 'linkOptions' => ['target' => '_blank', 'rel' => 'noopener']],
                    ['label' => 'Account', 'header' => true, 'visible' => !Yii::$app->user->isGuest],
                    ['label' => 'Logout', 'icon' => 'sign-out-alt', 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post'], 'visible' => !Yii::$app->user->isGuest],
                ],
            ]) ?>
        </nav>
    </div>
</aside>
