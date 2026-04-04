<?php

/** @var yii\web\View $this */
/** @var common\models\Language $model */

$this->title = 'Create Language';
$this->params['breadcrumbs'][] = ['label' => 'Languages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="language-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
