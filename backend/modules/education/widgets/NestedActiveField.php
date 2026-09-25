<?php

namespace backend\modules\education\widgets;

use yii\bootstrap4\ActiveField;
use yii\helpers\Html;

class NestedActiveField extends ActiveField
{
    public function error($options = [])
    {
        if ($options === false) {
            $this->parts['{error}'] = '';

            return $this;
        }

        $attribute = $this->attribute;
        $options = array_merge($this->errorOptions, $options);
        $options['errorSource'] = static function ($model, string $normalizedAttribute) use ($attribute): string {
            return (string)$model->getFirstError($attribute);
        };
        $this->parts['{error}'] = Html::error($this->model, $attribute, $options);

        return $this;
    }

    protected function addErrorClassIfNeeded(&$options)
    {
        if ($this->model->hasErrors($this->attribute)) {
            Html::addCssClass($options, $this->form->errorCssClass);
        }
    }
}
