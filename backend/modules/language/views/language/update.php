<?php

/** @var yii\web\View $this */
/** @var common\models\Language $model */

$this->title = 'Update Language: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Languages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="language-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
