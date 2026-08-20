<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $news_categories_id
 * @property string $lang
 * @property string $title
 *
 * @property NewsCategories $news_categories
 */
class NewsCategoriesMl extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%news_categories_ml}}';
    }

    public function rules(): array
    {
        return [
            [['news_categories_id', 'lang', 'title'], 'required'],
            [['news_categories_id'], 'integer'],
            [['lang'], 'string', 'max' => 8],
            [['title'], 'string', 'max' => 255],
            [['news_categories_id', 'lang'], 'unique', 'targetAttribute' => ['news_categories_id', 'lang']],
            [['news_categories_id'], 'exist', 'targetClass' => NewsCategories::class, 'targetAttribute' => ['news_categories_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'news_categories_id' => 'News Category',
            'lang' => 'Language',
            'title' => 'Title',
        ];
    }

    public function getNewsCategories()
    {
        return $this->hasOne(NewsCategories::class, ['id' => 'news_categories_id']);
    }
}
