<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $frontend_language_id
 * @property string $lang
 * @property string $text
 *
 * @property FrontendLanguage $frontendLanguage
 */
class FrontendLanguageMl extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%frontend_languages_ml}}';
    }

    public function rules(): array
    {
        return [
            [['frontend_language_id', 'lang', 'text'], 'required'],
            [['frontend_language_id'], 'integer'],
            [['text'], 'string'],
            [['lang'], 'string', 'max' => 8],
            [['frontend_language_id', 'lang'], 'unique', 'targetAttribute' => ['frontend_language_id', 'lang']],
            [['frontend_language_id'], 'exist', 'targetClass' => FrontendLanguage::class, 'targetAttribute' => ['frontend_language_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'frontend_language_id' => 'Frontend Language',
            'lang' => 'Language',
            'text' => 'Text',
        ];
    }

    public function getFrontendLanguage()
    {
        return $this->hasOne(FrontendLanguage::class, ['id' => 'frontend_language_id']);
    }
}
