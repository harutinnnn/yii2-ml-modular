<?php

namespace common\components;

use common\models\FrontendLanguage;
use common\models\Texts;
use Yii;

class I18n
{

    public static function translate($key = '')
    {
        return Yii::$app->globalData->labels[$key] ?? $key;
    }

    public static function translatePlural($key = '', $lang = 'en')
    {
        $text = FrontendLanguage::findOne(['key' => $key]);
        $translation = $text->getTranslation($lang);
        return $translation->text ?? $key;
    }

}