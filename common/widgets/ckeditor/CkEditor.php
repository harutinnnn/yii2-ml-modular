<?php

namespace common\widgets\ckeditor;

use mihaildev\elfinder\ElFinder;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

class CkEditor extends InputWidget
{
    public array $clientOptions = [];
    public string|array|null $elfinderController = null;

    public function run(): string
    {
        $this->options['id'] = $this->options['id'] ?? $this->getId();

        $input = $this->hasModel()
            ? Html::activeTextarea($this->model, $this->attribute, $this->options)
            : Html::textarea($this->name, $this->value, $this->options);

        $this->registerAssets();

        return $input;
    }

    protected function registerAssets(): void
    {
        CkEditorAsset::register($this->view);

        $clientOptions = $this->clientOptions;
        if ($this->elfinderController !== null) {
            $clientOptions = ElFinder::ckeditorOptions($this->elfinderController, $clientOptions);
        }

        $options = Json::encode($clientOptions);
        $id = Json::htmlEncode($this->options['id']);

        $this->view->registerJs("CKEDITOR.replace({$id}, {$options});");
    }
}
