<?php

/** @var yii\web\View $this */
/** @var backend\modules\news\models\NewsCategoriesForm $model */

$this->title = 'Update News Category #' . $model->news_categories?->id;
$this->params['breadcrumbs'][] = ['label' => 'News category', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['model' => $model]) ?>
