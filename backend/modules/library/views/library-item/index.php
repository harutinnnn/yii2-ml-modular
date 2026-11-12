<?php

/** @var yii\web\View $this */
/** @var backend\modules\library\models\LibraryItemSearch $searchModel */

/** @var yii\data\ActiveDataProvider $dataProvider */

use common\components\StatusList;
use common\models\LibraryItem;
use common\models\LibraryModule;
use common\models\TranslationModel;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Electronic Library';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="post-index">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <?= Html::a('Create Library item', ['create'], ['class' => 'btn btn-primary']) ?>
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
                            [
                                    'attribute' => 'title',
                                    'value' => static fn($model) => TranslationModel::getDisplayTitle($model),
                            ],
                            [
                                    'attribute' => 'status',
                                    'filter' => LibraryItem::statusOptions(),
                                    'value' => static fn($model) => StatusList::getStatusLabel($model->status),
                            ],
                            [
                                    'attribute' => 'type',
                                    'filter' => LibraryItem::optsType(),
                                    'value' => static fn($model) => LibraryItem::optsType()[$model->type],
                            ],
                            [
                                    'attribute' => 'library_module_id',
                                    'filter' => LibraryModule::getModulesKeyVal(),
                                    'value' => static fn($model) => LibraryModule::getModulesKeyVal()[$model->library_module_id],
                            ],
                            [
                                    'class' => ActionColumn::class,
                                    'header' => 'Actions',
                                    'template' => '{update} {delete}',
                                    'contentOptions' => ['class' => 'text-nowrap'],
                                    'buttons' => [
                                            'update' => static fn($url, $model) => Html::a('Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-sm mr-1']),
                                            'delete' => static fn($url, $model) => Html::a('Remove', ['delete', 'id' => $model->id], [
                                                    'class' => 'btn btn-danger btn-sm',
                                                    'data-method' => 'post',
                                                    'data-confirm' => 'Are you sure you want to delete this item?',
                                            ]),
                                    ],
                            ],
                    ],
            ]) ?>
        </div>
    </div>
</div>
