<?php

/** @var yii\web\View $this */

/** @var backend\modules\education\models\EducationLevelsForm $model */


use common\models\EducationLevels;
use backend\modules\education\widgets\NestedActiveField;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$languages = $model->getLanguages();
$editorConfigs = [];
$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->csrfToken;
?>

<div class="post-form">
    <?php $form = ActiveForm::begin([
            'fieldClass' => NestedActiveField::class,
            'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>

    <div class="card card-primary">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'status')->dropDownList(EducationLevels::statusOptions()) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'pos')->textInput() ?>
                </div>
            </div>

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

                        <?= $form->field($model, "translations[{$language->code}][imgFile]")
                                ->label("Image ({$language->name})")
                                ->fileInput([
                                        'accept' => '.png,.jpg,.jpeg,.gif,.webp,image/png,image/jpeg,image/gif,image/webp',
                                ]) ?>

                        <?php $currentImage = $model->getImage($language->code); ?>
                        <?php if ($currentImage): ?>
                            <div class="mb-3">
                                <?= Html::img($currentImage, [
                                        'alt' => "Current image ({$language->name})",
                                        'class' => 'img-thumbnail',
                                        'style' => 'max-width: 240px; max-height: 180px;',
                                ]) ?>
                            </div>
                        <?php endif; ?>

                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="card-footer">
            <?= Html::submitButton($model->education_levels === null ? 'Create' : 'Update', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
