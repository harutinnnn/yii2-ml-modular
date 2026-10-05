<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "message_reads".
 *
 * @property int $message_id
 * @property int $user_id
 * @property string|null $read_at
 *
 * @property Messages $message
 */
class MessageReads extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message_reads';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message_id', 'user_id'], 'required'],
            [['message_id', 'user_id'], 'integer'],
            [['read_at'], 'safe'],
            [['message_id', 'user_id'], 'unique', 'targetAttribute' => ['message_id', 'user_id']],
            [['message_id'], 'exist', 'skipOnError' => true, 'targetClass' => Messages::class, 'targetAttribute' => ['message_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'message_id' => 'Message ID',
            'user_id' => 'User ID',
            'read_at' => 'Read At',
        ];
    }

    /**
     * Gets query for [[Message]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessage()
    {
        return $this->hasOne(Messages::class, ['id' => 'message_id']);
    }

}
