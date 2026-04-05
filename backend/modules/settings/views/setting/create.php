<?php

/** @var yii\web\View $this */
/** @var common\models\Setting $model */

$this->title = 'Create Setting';
$this->params['breadcrumbs'][] = ['label' => 'Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="setting-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
