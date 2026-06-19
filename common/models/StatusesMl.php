<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "statuses_ml".
 *
 * @property int $id
 * @property int $status_id
 * @property string $lang
 * @property string|null $title
 *
 * @property Statuses $status
 */
class StatusesMl extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%statuses_ml}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'default', 'value' => null],
            [['status_id', 'lang'], 'required'],
            [['status_id'], 'integer'],
            [['lang'], 'string', 'max' => 8],
            [['title'], 'string', 'max' => 255],
            [['status_id', 'lang'], 'unique', 'targetAttribute' => ['status_id', 'lang']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Statuses::class, 'targetAttribute' => ['status_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status_id' => 'Status ID',
            'lang' => 'Lang',
            'title' => 'Title',
        ];
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Statuses::class, ['id' => 'status_id']);
    }

}
