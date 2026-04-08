<?php

namespace common\helpers;
class I18n
{

    public static function translate(string $key)
    {
        return \Yii::$app->appState->getLabels()[$key];
    }

}