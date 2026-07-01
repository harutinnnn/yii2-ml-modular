<?php

use common\components\RbacUtilities;
use common\components\UserRoles;
use yii\helpers\Html;
use yii\helpers\Url;

$email = Yii::$app->user->isGuest ? 'Guest' : Yii::$app->user->identity->email;
$menuItems = [

        ['label' => 'Dashboard', 'icon' => 'tachometer-alt', 'url' => ['/site/index'],
                'visible' => RbacUtilities::allowRoles(['admin', 'teacher'])
        ],


        [
                'label' => 'Users',
                'icon' => 'users',
                'visible' => RbacUtilities::allowRoles(['admin']),
                'items' => [
                        ['label' => 'Applicant', 'icon' => 'users', 'url' => ['/user/applicant/index'],
                                'visible' => RbacUtilities::allowRoles(['admin']),
                                'active' => Yii::$app->controller->id == 'applicant'
                        ],
                        ['label' => 'Students', 'icon' => 'users', 'url' => ['/user/student/index'],
                                'visible' => RbacUtilities::allowRoles(['admin']),
                                'active' => Yii::$app->controller->id == 'student'
                        ],
                        ['label' => 'Teachers', 'icon' => 'chalkboard-teacher', 'url' => ['/user/teacher/index'],
                                'visible' => RbacUtilities::allowRoles(['admin']),
                                'active' => Yii::$app->controller->id == 'teacher'
                        ],
                        ['label' => 'Admins', 'icon' => 'users-cog', 'url' => ['/user/admin/index'],
                                'visible' => RbacUtilities::allowRoles(['admin']),
                                'active' => Yii::$app->controller->id == 'admin'
                        ],
                ]
        ],

        [
                'label' => 'Menus/Contents',
                'icon' => 'bars',
                'visible' => RbacUtilities::allowRoles(['admin']),
                'items' => [
                        ['label' => 'Sections', 'icon' => 'folder', 'url' => ['/section/section/index'],
                                'visible' => RbacUtilities::allowRoles(['admin']),
                                'active' => Yii::$app->controller->id == 'section'
                        ],
                        ['label' => 'Menu', 'icon' => 'bars', 'url' => ['/menu/menu/index'],
                                'visible' => RbacUtilities::allowRoles(['admin']),
                                'active' => Yii::$app->controller->id == 'menu'
                        ],
                        ['label' => 'Content Items', 'icon' => 'copy', 'url' => ['/content/content/index'],
                                'visible' => RbacUtilities::allowRoles(['moderator', 'admin']),
                                'active' => Yii::$app->controller->id == 'content'
                        ],
                        ['label' => 'Email Contents', 'icon' => 'envelope', 'url' => ['/email-content/email-content/index'],
                                'visible' => RbacUtilities::allowRoles(['admin']),
                                'active' => Yii::$app->controller->id == 'email-content'
                        ],
                ]
        ],

        [
                'label' => 'Faculties/Chairs',
                'icon' => 'user-graduate',
                'visible' => RbacUtilities::allowRoles(['admin']),
                'items' => [
                        ['label' => 'Faculties', 'icon' => 'folder', 'url' => ['/faculties/faculty/index'],
                                'visible' => RbacUtilities::allowRoles(['admin']),
                                'active' => Yii::$app->controller->id == 'faculty'
                        ],
                        ['label' => 'Chairs', 'icon' => 'chalkboard-teacher', 'url' => ['/faculties/chair/index'],
                                'visible' => RbacUtilities::allowRoles(['admin']),
                                'active' => Yii::$app->controller->id == 'chair'
                        ],
                ]
        ],


        [
                'label' => 'Journals',
                'icon' => 'newspaper',
                'visible' => RbacUtilities::allowRoles(['admin']),
                'items' => [
                        ['label' => 'Scientific journal', 'icon' => 'newspaper', 'url' => ['/journal/journal/index'],
                                'visible' => RbacUtilities::allowRoles(['moderator', 'admin']),
                                'active' => Yii::$app->controller->id == 'journal'
                        ],
                        ['label' => 'Journal authors', 'icon' => 'newspaper', 'url' => ['/journal/journal-authors/index'],
                                'visible' => RbacUtilities::allowRoles(['moderator', 'admin']),
                                'active' => Yii::$app->controller->id == 'journal-authors'
                        ],
                ]
        ],





        ['label' => 'Posts', 'icon' => 'file-alt', 'url' => ['/posts/post/index'],
                'visible' => RbacUtilities::allowRoles(['teacher', 'admin']),
                'active' => Yii::$app->controller->id == 'post'
        ],


        [
                'label' => 'Languages/Settings',
                'icon' => 'sliders-h',
                'visible' => RbacUtilities::allowRoles(['admin']),
                'items' => [

                        ['label' => 'Frontend Languages', 'icon' => 'tags', 'url' => ['/frontend-language/frontend-language/index'],
                                'visible' => RbacUtilities::allowRoles(['admin']),
                                'active' => Yii::$app->controller->id == 'frontend-language'
                        ],

                        ['label' => 'Languages', 'icon' => 'language', 'url' => ['/language/language/index'],
                                'visible' => RbacUtilities::allowRoles(['admin']),
                                'active' => Yii::$app->controller->id == 'language'
                        ],

                        ['label' => 'Settings', 'icon' => 'cogs', 'url' => ['/settings/setting/index'],
                                'visible' => RbacUtilities::allowRoles(['admin']),
                                'active' => Yii::$app->controller->id == 'setting'
                        ],
                        ['label' => 'Statuses', 'icon' => 'toggle-off', 'url' => ['/statuses/status/index'],
                                'visible' => RbacUtilities::allowRoles(['admin']),
                                'active' => Yii::$app->controller->id == 'status'
                        ],

                ]
        ],


        [
                'label' => 'RBAC',
                'icon' => 'user-shield',
                'visible' => RbacUtilities::allowRoles([UserRoles::SUPER_ADMIN]),
                'items' => [
                        [
                                'label' => 'Assignment',
                                'icon' => 'users',
                                'url' => ['/admin/assignment/index'],
                                'visible' => RbacUtilities::allowRoles([UserRoles::SUPER_ADMIN]),
                                'active' => Yii::$app->controller->id == 'assignment'
                        ],
                        [
                                'label' => 'Role',
                                'icon' => 'user-tag',
                                'url' => ['/admin/role/index'],
                                'visible' => RbacUtilities::allowRoles([UserRoles::SUPER_ADMIN]),
                                'active' => Yii::$app->controller->id == 'role'
                        ],

//                        [
//                                'label' => 'Permission',
//                                'icon' => 'lock',
//                                'url' => ['/admin/permission/index'],
//                                'visible' => RbacUtilities::allowRoles([UserRoles::SUPER_ADMIN])
//                        ],
//
//                        [
//                                'label' => 'Route',
//                                'icon' => 'route',
//                                'url' => ['/admin/route/index'],
//                                'visible' => RbacUtilities::allowRoles([UserRoles::SUPER_ADMIN])
//                        ],
                ],
        ],


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
                <a href="<?= Url::to(['/site/index']) ?>" class="d-block"><?= Html::encode($email) ?></a>
            </div>
        </div>

        <nav class="mt-2">
            <?= \hail812\adminlte\widgets\Menu::widget([
                    'items' => $menuItems,
            ]) ?>
        </nav>
    </div>
</aside>
