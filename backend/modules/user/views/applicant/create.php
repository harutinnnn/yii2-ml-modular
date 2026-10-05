<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Applicant $model */

$this->title = 'Create Applicant';
$this->params['breadcrumbs'][] = ['label' => 'Applicants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<?= $this->render('_form', [
        'model' => $model,
]) ?>

