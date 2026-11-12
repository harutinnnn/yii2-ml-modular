<?php

namespace common\models;

use common\components\StatusList;
use Yii;

/**
 * This is the model class for table "news_tags".
 *
 * @property int $id
 * @property int $status
 *
 * @property NewsTagsMl[] $newsTagsMls
 */
class NewsTags extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news_tags';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'default', 'value' => 0],
            [['status'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
        ];
    }

    public static function statusOptions(): array
    {

        return [
            StatusList::STATUS_PENDING => 'Pending',
            StatusList::STATUS_PUBLISHED => 'Published',
        ];
    }

    /**
     * Gets query for [[NewsTagsMls]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(NewsTagsMl::class, ['tag_id' => 'id'])->indexBy('lang');
    }

}
