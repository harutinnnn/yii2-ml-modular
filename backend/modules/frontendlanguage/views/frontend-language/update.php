<?php

/** @var yii\web\View $this */
/** @var backend\modules\frontendlanguage\models\FrontendLanguageForm $model */

$this->title = 'Update Frontend Language Item #' . $model->frontendLanguage?->id;
$this->params['breadcrumbs'][] = ['label' => 'Frontend Languages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['model' => $model]) ?>
