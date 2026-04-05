<?php

/** @var yii\web\View $this */
/** @var common\models\Setting $model */

$this->title = 'Update Setting: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="setting-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
