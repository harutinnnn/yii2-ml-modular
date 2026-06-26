<?php

/** @var yii\web\View $this */
/** @var backend\modules\faculties\models\ChairsForm $model */

$this->title = 'Update Chair #' . $model->chairs?->id;
$this->params['breadcrumbs'][] = ['label' => 'Chairs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['model' => $model,'faculties' => $faculties ?? []]) ?>
