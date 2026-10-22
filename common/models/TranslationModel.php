<?php

namespace common\models;

class TranslationModel extends \yii\db\ActiveRecord
{


    public static function getDisplayTitle($model, $attr = 'title'): string
    {
        $defaultLanguage = Language::find()->where(['is_default' => 1])->select('code')->scalar();

        if ($defaultLanguage) {

            return self::getTranslation($model, $defaultLanguage);
        }

        foreach ($model->translations as $translation) {
            if ($translation->{$attr} !== '') {
                return $translation->{$attr};
            }
        }

        return 'Untitled';
    }


    public static function getTranslation($model, $lang, $attr = 'title'): string|null
    {
        $translations = $model->translations;

        return $translations[$lang]->{$attr} ?? null;
    }
}