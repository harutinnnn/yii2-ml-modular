<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "announcement_tags_lcp".
 *
 * @property int $news_id
 * @property int $tag_id
 */
class AnnouncementTagsLcp extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'announcement_tags_lcp';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['news_id', 'tag_id'], 'required'],
            [['news_id', 'tag_id'], 'integer'],
            [['news_id', 'tag_id'], 'unique', 'targetAttribute' => ['news_id', 'tag_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'news_id' => 'News ID',
            'tag_id' => 'Tag ID',
        ];
    }

}
