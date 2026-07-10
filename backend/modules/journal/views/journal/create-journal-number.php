<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\JournalNumbers $model */

$this->title = 'Create Journal Number';
$this->params['breadcrumbs'][] = ['label' => 'Journal', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Journal Numbers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="journal-numbers-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_journal-number-form', [
        'model' => $model,
    ]) ?>

</div>
