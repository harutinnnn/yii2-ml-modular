<?php

/** @var yii\web\View $this */
/** @var backend\modules\language\models\LanguageSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Languages';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="language-index">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="m-0"><?= Html::encode($this->title) ?></h1>
        <?= Html::a('Create Language', ['create'], ['class' => 'btn btn-primary']) ?>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-hover mb-0'],
                'layout' => "{items}\n<div class=\"card-footer clearfix\">{summary}{pager}</div>",
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'code',
                    'name',
                    [
                        'attribute' => 'sort_order',
                    ],
                    [
                        'attribute' => 'is_default',
                        'format' => 'boolean',
                        'filter' => [1 => 'Yes', 0 => 'No'],
                    ],
                    [
                        'attribute' => 'is_active',
                        'format' => 'boolean',
                        'filter' => [1 => 'Yes', 0 => 'No'],
                    ],
                    [
                        'class' => ActionColumn::class,
                        'header' => 'Actions',
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
