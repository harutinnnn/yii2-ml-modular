<?php

/** @var yii\web\View $this */
/** @var backend\modules\emailcontent\models\EmailContentForm $model */

$this->title = 'Update Email Content #' . $model->emailContent?->id;
$this->params['breadcrumbs'][] = ['label' => 'Email Contents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['model' => $model]) ?>
