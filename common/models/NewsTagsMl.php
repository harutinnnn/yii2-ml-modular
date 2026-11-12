<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "news_tags_ml".
 *
 * @property int $id
 * @property int $tag_id
 * @property string $lang
 * @property string $title
 *
 * @property NewsTags $tag
 */
class NewsTagsMl extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news_tags_ml';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tag_id', 'lang', 'title'], 'required'],
            [['tag_id'], 'integer'],
            [['lang'], 'string', 'max' => 8],
            [['title'], 'string', 'max' => 255],
            [['tag_id', 'lang'], 'unique', 'targetAttribute' => ['tag_id', 'lang']],
            [['tag_id'], 'exist', 'skipOnError' => true, 'targetClass' => NewsTags::class, 'targetAttribute' => ['tag_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tag_id' => 'Tag ID',
            'lang' => 'Lang',
            'title' => 'Title',
        ];
    }

    /**
     * Gets query for [[Tag]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTag()
    {
        return $this->hasOne(NewsTags::class, ['id' => 'tag_id']);
    }

}
