<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $chair_id
 * @property string $lang
 * @property string $title
 * @property string $text
 * @property string $about_chair
 *
 * @property Chairs $chairs
 */
class ChairsMl extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%chairs_ml}}';
    }

    public function rules(): array
    {
        return [
            [['chair_id', 'lang', 'title','about_chair'], 'required'],
            [['chair_id'], 'integer'],
            [['text','about_chair'], 'string'],
            [['lang'], 'string', 'max' => 8],
            [['title'], 'string', 'max' => 255],
            [['chair_id', 'lang'], 'unique', 'targetAttribute' => ['chair_id', 'lang']],
            [['chair_id'], 'exist', 'targetClass' => Chairs::class, 'targetAttribute' => ['chair_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'chair_id' => 'Chair',
            'lang' => 'Language',
            'title' => 'Title',
            'text' => 'Text',
            'about_chair' => 'About chair',
        ];
    }

    public function getChair()
    {
        return $this->hasOne(Chairs::class, ['id' => 'chair_id']);
    }
}
