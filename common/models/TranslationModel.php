<?php

namespace common\models;

class TranslationModel extends \yii\db\ActiveRecord
{


    public  static function getDisplayTitle($model): string
    {
        $defaultLanguage = Language::find()->where(['is_default' => 1])->select('code')->scalar();

        if ($defaultLanguage) {
            $translation = $model->getTranslation($defaultLanguage);
            if ($translation !== null && $translation->title !== '') {
                return $translation->title;
            }
        }

        foreach ($model->translations as $translation) {
            if ($translation->title !== '') {
                return $translation->title;
            }
        }

        return 'Untitled';
    }

}