<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $news_id
 * @property string $lang
 * @property string $title
 * @property string $text
 *
 * @property News $news
 */
class NewsMl extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%news_ml}}';
    }

    public function rules(): array
    {
        return [
            [['news_id', 'lang', 'title', 'text'], 'required'],
            [['news_id'], 'integer'],
            [['text'], 'string'],
            [['lang'], 'string', 'max' => 8],
            [['title'], 'string', 'max' => 255],
            [['news_id', 'lang'], 'unique', 'targetAttribute' => ['news_id', 'lang']],
            [['news_id'], 'exist', 'targetClass' => News::class, 'targetAttribute' => ['news_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'news_id' => 'News',
            'lang' => 'Language',
            'title' => 'Title',
            'text' => 'Text',
        ];
    }

    public function getNews()
    {
        return $this->hasOne(News::class, ['id' => 'news_id']);
    }
}
