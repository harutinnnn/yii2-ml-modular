<?php

use yii\helpers\Html;
use yii\helpers\Url;

$username = Yii::$app->user->isGuest ? 'Guest' : Yii::$app->user->identity->username;
?>
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?= Url::to(['/site/index']) ?>" class="nav-link">Dashboard</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?= Url::to(['/']) ?>" class="nav-link" target="_blank" rel="noopener">View Site</a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                <i class="far fa-user"></i>
                <span class="ml-1"><?= Html::encode($username) ?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item-text text-muted"><?= Html::encode($username) ?></span>
                <div class="dropdown-divider"></div>
                <?= Html::a('Logout', ['/site/logout'], ['data-method' => 'post', 'class' => 'dropdown-item']) ?>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
