<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Applicant $model */

$this->title = 'Create Staff';
$this->params['breadcrumbs'][] = ['label' => 'Staff', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<?= $this->render('_form', [
        'model' => $model,
]) ?>

