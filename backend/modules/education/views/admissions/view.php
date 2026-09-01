<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Admissions $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Admissions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="admissions-view">
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0"><?= $model->name . ' '. $model->surname ?></h3>
            <div>
                <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary btn-sm']) ?>
            </div>
        </div>

        <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                        'name',
                        'surname',
                        'email:email',
                        'phone',
                        [
                                'attribute' => 'education_level',
                                'filter' => $levels ?? [],
                                'value' => static fn($model) => $levels[$model->education_level] ?? '-',
                        ],
                        [
                                'attribute' => 'educational_programs',
                                'filter' => $programs ?? [],
                                'value' => static fn($model) => $programs[$model->educational_programs] ?? '-',
                        ],
                        'created_at:date',
                        'updated_at:date',
                ],
        ]) ?>

    </div>
</div>
