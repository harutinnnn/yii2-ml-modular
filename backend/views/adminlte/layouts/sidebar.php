<?php

use common\components\RbacUtilities;
use yii\helpers\Html;
use yii\helpers\Url;

$username = Yii::$app->user->isGuest ? 'Guest' : Yii::$app->user->identity->username;
$menuItems = [

        ['label' => 'Dashboard', 'icon' => 'tachometer-alt', 'url' => ['/site/index'],
                'visible' => RbacUtilities::allowRoles(['admin', 'teacher'])
        ],

        ['label' => 'Users', 'icon' => 'users', 'url' => ['/user/user/index'],
                'visible' => RbacUtilities::allowRoles(['admin'])
        ],

        ['label' => 'Menu', 'icon' => 'bars', 'url' => ['/menu/menu/index'],
                'visible' => RbacUtilities::allowRoles(['admin'])
        ],
        ['label' => 'Content', 'header' => true],

        ['label' => 'Content Items', 'icon' => 'copy', 'url' => ['/content/content/index'],
                'visible' => RbacUtilities::allowRoles(['moderator', 'admin'])
        ],

        ['label' => 'Posts', 'icon' => 'file-alt', 'url' => ['/posts/post/index'],
                'visible' => RbacUtilities::allowRoles(['teacher', 'admin'])
        ],

        ['label' => 'Email Contents', 'icon' => 'envelope', 'url' => ['/email-content/email-content/index'],
                'visible' => RbacUtilities::allowRoles(['admin'])
        ],

        ['label' => 'Frontend Languages', 'icon' => 'tags', 'url' => ['/frontend-language/frontend-language/index'],
                'visible' => RbacUtilities::allowRoles(['admin'])
        ],

        ['label' => 'Languages', 'icon' => 'language', 'url' => ['/language/language/index'],
                'visible' => RbacUtilities::allowRoles(['admin'])
        ],

        ['label' => 'Sections', 'icon' => 'folder', 'url' => ['/section/section/index'],
                'visible' => RbacUtilities::allowRoles(['admin'])
        ],

        ['label' => 'Settings', 'icon' => 'cogs', 'url' => ['/settings/setting/index'],
                'visible' => RbacUtilities::allowRoles(['admin'])
        ],
        ['label' => 'Statuses', 'icon' => 'toggle-off', 'url' => ['/statuses/status/index'],
                'visible' => RbacUtilities::allowRoles(['admin'])
        ],

        ['label' => 'RBAC', 'header' => true,
                'visible' => RbacUtilities::allowRoles(['admin'])
        ],

        ['label' => 'Assignment', 'icon' => 'users', 'url' => ['/admin/assignment/index'],
                'visible' => RbacUtilities::allowRoles(['admin'])
        ],
//        ['label' => 'Permission', 'icon' => 'lock', 'url' => ['/admin/permission/index'],
//                'visible' => RbacUtilities::allowRoles(['admin'])
//        ],
        ['label' => 'Role', 'icon' => 'user-tag', 'url' => ['/admin/role/index'],
                'visible' => RbacUtilities::allowRoles(['admin'])
        ],
//        ['label' => 'Route', 'icon' => 'route', 'url' => ['/admin/route/index'],
//                'visible' => RbacUtilities::allowRoles(['admin'])
//        ],

        ['label' => 'Yii Tools', 'header' => true, 'visible' => YII_ENV_DEV && RbacUtilities::allowRoles(['admin'])],

        ['label' => 'Gii', 'icon' => 'file-code', 'url' => ['/gii'], 'target' => '_blank', 'visible' => YII_ENV_DEV && RbacUtilities::allowRoles(['admin'])],

        ['label' => 'Debug', 'icon' => 'bug', 'url' => ['/debug'], 'target' => '_blank', 'visible' => YII_ENV_DEV && RbacUtilities::allowRoles(['admin'])],
];

?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?= Url::to(['/site/index']) ?>" class="brand-link">
        <img src="<?= $assetDir ?>/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
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
                    'items' => $menuItems,
            ]) ?>
        </nav>
    </div>
</aside>
