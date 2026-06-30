<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\JournalAuthors $model */

$this->title = 'View Chair #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Journal Authors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="journal-authors-view">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0"><?= $model->first_name . ' ' . $model->last_name ?></h3>
            <div>
                <?= Html::a('Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-sm']) ?>
                <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary btn-sm']) ?>
            </div>
        </div>

        <div class="card-body">
            <p><strong>ID:</strong> <?= Html::encode($model->id) ?></p>


            <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                            'id',
                            'first_name',
                            'last_name',
                            'bio:raw',
                            'img',
                    ],
            ]) ?>

        </div>
    </div>

</div>
