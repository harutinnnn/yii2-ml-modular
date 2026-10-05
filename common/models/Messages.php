<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "messages".
 *
 * @property int $id
 * @property int $conversation_id
 * @property int $sender_id
 * @property string $message
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Conversations $conversation
 * @property MessageReads[] $messageReads
 */
class Messages extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'messages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['updated_at'], 'default', 'value' => null],
            [['conversation_id', 'sender_id', 'message'], 'required'],
            [['conversation_id', 'sender_id'], 'integer'],
            [['message'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['conversation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Conversations::class, 'targetAttribute' => ['conversation_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'conversation_id' => 'Conversation ID',
            'sender_id' => 'Sender ID',
            'message' => 'Message',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Conversation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConversation()
    {
        return $this->hasOne(Conversations::class, ['id' => 'conversation_id']);
    }

    /**
     * Gets query for [[MessageReads]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessageReads()
    {
        return $this->hasMany(MessageReads::class, ['message_id' => 'id']);
    }

}
