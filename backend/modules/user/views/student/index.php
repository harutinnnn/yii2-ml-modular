<?php

use common\models\Chairs;
use common\models\TranslationModel;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\modules\user\models\StudentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */


$chairs = ArrayHelper::map(
        Chairs::find()->all(), 'id', function ($model) {
    return TranslationModel::getTranslation($model, 'en');
});

$this->title = 'Students';
$this->params['breadcrumbs'][] = $this->title;
?>

    <div class="student-index">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <?= Html::button('Փոխադրել հաջորդ կուրս', [
                    'class' => 'btn btn-success',
                    'id' => 'move-course'
            ]) ?>
        </div>


        <div class="card">

            <div class="card-body p-0">

                <?= GridView::widget([
                        'id' => 'my-grid',
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'tableOptions' => ['class' => 'table table-hover mb-0'],
                        'layout' => "{items}\n<div class=\"card-footer clearfix\">{summary}{pager}</div>",
                        'columns' => [
                                [
                                        'class' => 'yii\grid\CheckboxColumn',
                                ],
                                'email',
                                [
                                        'attribute' => 'first_name',
                                        'value' => 'additional.first_name',
                                ],
                                [
                                        'attribute' => 'last_name',
                                        'value' => 'additional.last_name',
                                ],
                                [
                                        'attribute' => 'course',
                                        'value' => 'additional.course',
                                ],
                                [
                                        'attribute' => 'chair',
                                        'value' => static fn($model) => $chairs[$model->additional->chair],
                                        'filter' => $chairs,
                                ],
//                            [
//                                    'attribute' => 'status',
//                                    'filter' => \common\models\Student::statusOptions(),
//                                    'value' => static fn($model) => $model->getStatusLabel(),
//                            ],
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
                ]); ?>

            </div>
        </div>

    </div>

<?php
$this->registerJs(<<<JS
    $('#move-course').on('click', function () {
        const ids = $('#my-grid').yiiGridView('getSelectedRows');

        if (ids.length === 0) {
            alert('Please select at least one row.');
            return;
        }

        
        console.log(ids);
        
        if(confirm('Are you sure you want to delete this item?')){
            
            $.ajax({
                type:'post',
                url: '/admin/user/student/move-course',
                data: {ids: ids},
                beforeSend: function(data){
                    $('#move-course').prop('disabled', true);     
                },
                success: function(data){
                    
                    window.location.reload();
                    
                    $('#move-course').prop('disabled', false);
                },error: function(error){
                    
                    $('#move-course').prop('disabled', false);
                }
            });
            
        }
        
    });

JS, \yii\web\View::POS_END);
?>