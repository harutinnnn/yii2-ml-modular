<?php

/** @var yii\web\View $this */
/** @var backend\modules\frontendlanguage\models\FrontendLanguageForm $model */

$this->title = 'Create Frontend Language Item';
$this->params['breadcrumbs'][] = ['label' => 'Frontend Languages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['model' => $model]) ?>
