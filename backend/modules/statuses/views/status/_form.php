<?php

/** @var yii\web\View $this */
/** @var backend\modules\statuses\models\StatusesForm $model */

use common\helpers\EditorJsHelper;
use common\models\Statuses;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\Json;
use yii\helpers\Url;

$languages = $model->getLanguages();
$editorConfigs = [];
$uploadImageUrl = Url::to(['upload-image']);
$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->csrfToken;
?>

<div class="statuse-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="card card-primary">
        <div class="card-body">
            <?= $form->field($model, 'status')->dropDownList(Statuses::statusOptions()) ?>
        </div>
    </div>

    <div class="card card-outline card-primary">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" role="tablist">
                <?php foreach ($languages as $index => $language): ?>
                    <li class="nav-item">
                        <a
                            class="nav-link <?= $index === 0 ? 'active' : '' ?>"
                            id="lang-tab-<?= Html::encode($language->code) ?>"
                            data-toggle="pill"
                            href="#lang-pane-<?= Html::encode($language->code) ?>"
                            role="tab"
                            aria-controls="lang-pane-<?= Html::encode($language->code) ?>"
                            aria-selected="<?= $index === 0 ? 'true' : 'false' ?>"
                        >
                            <?= Html::encode(strtoupper($language->code)) ?>: <?= Html::encode($language->name) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <?php foreach ($languages as $index => $language): ?>
                    <div
                        class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>"
                        id="lang-pane-<?= Html::encode($language->code) ?>"
                        role="tabpanel"
                        aria-labelledby="lang-tab-<?= Html::encode($language->code) ?>"
                    >
                        <?= $form->field($model, "translations[{$language->code}][title]")
                            ->label("Title ({$language->name})")
                            ->textInput([
                                'maxlength' => true,
                                'placeholder' => "Title in {$language->name}",
                            ]) ?>



                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="card-footer">
            <?= Html::submitButton($model->status === null ? 'Create' : 'Update', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>