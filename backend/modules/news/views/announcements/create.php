<?php

/** @var yii\web\View $this */
/** @var backend\modules\news\models\AnnouncementsForm $model */

$this->title = 'Create Announcements';
$this->params['breadcrumbs'][] = ['label' => 'Announcements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['model' => $model, 'categories' => $categories ?? []]) ?>
