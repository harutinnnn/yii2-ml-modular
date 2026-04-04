<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $content_id
 * @property string $lang
 * @property string $title
 * @property string $text
 *
 * @property Content $content
 */
class ContentMl extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%content_ml}}';
    }

    public function rules(): array
    {
        return [
            [['content_id', 'lang', 'title', 'text'], 'required'],
            [['content_id'], 'integer'],
            [['text'], 'string'],
            [['lang'], 'string', 'max' => 8],
            [['title'], 'string', 'max' => 255],
            [['content_id', 'lang'], 'unique', 'targetAttribute' => ['content_id', 'lang']],
            [['content_id'], 'exist', 'targetClass' => Content::class, 'targetAttribute' => ['content_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'content_id' => 'Content',
            'lang' => 'Language',
            'title' => 'Title',
            'text' => 'Text',
        ];
    }

    public function getContent()
    {
        return $this->hasOne(Content::class, ['id' => 'content_id']);
    }
}
