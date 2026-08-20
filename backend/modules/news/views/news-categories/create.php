<?php

/** @var yii\web\View $this */
/** @var backend\modules\news\models\NewsCategoriesForm $model */

$this->title = 'Create Post';
$this->params['breadcrumbs'][] = ['label' => 'News Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['model' => $model]) ?>
