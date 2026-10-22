<?php

namespace common\models;

use yii\db\ActiveRecord;
use yii\helpers\Inflector;

/**
 * @property int $id
 * @property int $news_id
 * @property string $lang
 * @property string $title
 * @property string $text
 * @property string $short
 * @property string $slug
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
            [['news_id', 'lang', 'title', 'text','short'], 'required'],
            [['news_id'], 'integer'],
            [['text','slug','short'], 'string'],
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
            'short' => 'Short',
            'text' => 'Text',
            'slug' => 'Slug',
        ];
    }

    public function getNews()
    {
        return $this->hasOne(News::class, ['id' => 'news_id']);
    }

    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $this->updateAttributes([
                'slug' => Inflector::slug($this->title).'-'.$this->news_id
            ]);
        } else {
            if (!$this->doi_suffix) {
                $this->updateAttributes([
                    'slug' => Inflector::slug($this->title).'-'.$this->news_id
                ]);
            }
        }
    }
}
