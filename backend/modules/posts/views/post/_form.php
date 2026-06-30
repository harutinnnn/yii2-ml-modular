<?php

/** @var yii\web\View $this */

/** @var backend\modules\posts\models\PostForm $model */

use common\helpers\EditorJsHelper;
use common\models\Post;
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

<div class="post-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="card card-primary">
        <div class="card-body">
            <?= $form->field($model, 'status')->dropDownList(Post::statusOptions()) ?>
            <?= $form->field($model, 'imageFile')->fileInput() ?>
            <?php if ($model->image): ?>
                <p class="mb-0"><img src="<?= Html::encode($model->image) ?>" alt="" style="max-height: 100px;"></p>
            <?php endif; ?>
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

                        <?= $form->field($model, "translations[{$language->code}][text]")
                                ->label("Text ({$language->name})")
                                ->hiddenInput(['id' => "post-text-{$language->code}"])
                                ->label("Text ({$language->name})") ?>
                        <div
                                class="border rounded p-3 bg-white js-editorjs-holder"
                                id="editorjs-<?= Html::encode($language->code) ?>"
                                style="min-height: 320px;padding-left: 60px !important;"
                        ></div>
                        <?php
                        $editorConfigs[] = [
                                'holderId' => "editorjs-{$language->code}",
                                'inputId' => "post-text-{$language->code}",
                                'data' => Json::decode(EditorJsHelper::encodeInitialData($model->translations[$language->code]['text'] ?? '')),
                        ];
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="card-footer">
            <?= Html::submitButton($model->post === null ? 'Create' : 'Update', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php
$editorConfigsJson = Json::htmlEncode($editorConfigs);
$uploadImageUrlJson = Json::htmlEncode($uploadImageUrl);
$csrfParamJson = Json::htmlEncode($csrfParam);
$csrfTokenJson = Json::htmlEncode($csrfToken);

$this->registerJsFile('https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/@editorjs/header@latest', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/@editorjs/list@latest', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/@editorjs/quote@latest', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/@editorjs/delimiter@latest', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/@editorjs/image@latest', ['position' => \yii\web\View::POS_END]);
$this->registerJs(<<<JS
    const configs = {$editorConfigsJson};
    const uploadImageUrl = {$uploadImageUrlJson};
    const csrfParam = {$csrfParamJson};
    const csrfToken = {$csrfTokenJson};
    const editors = [];
JS, \yii\web\View::POS_HEAD);

$this->registerJsFile('/admin/js/js-content-editor.js', ['position' => \yii\web\View::POS_END]);
?>
