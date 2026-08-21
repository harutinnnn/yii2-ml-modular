<?php

/** @var yii\web\View $this */
/** @var backend\modules\news\models\AnnouncementsForm $model */

$this->title = 'Update Announcements #' . $model->announcements?->id;
$this->params['breadcrumbs'][] = ['label' => 'Announcements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['model' => $model, 'categories' => $categories ?? []]) ?>
