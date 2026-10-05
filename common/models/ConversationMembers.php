<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "conversation_members".
 *
 * @property int $id
 * @property int $conversation_id
 * @property int $user_id
 * @property string|null $joined_at
 *
 * @property Conversations $conversation
 */
class ConversationMembers extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'conversation_members';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['conversation_id', 'user_id'], 'required'],
            [['conversation_id', 'user_id'], 'integer'],
            [['joined_at'], 'safe'],
            [['conversation_id', 'user_id'], 'unique', 'targetAttribute' => ['conversation_id', 'user_id']],
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
            'user_id' => 'User ID',
            'joined_at' => 'Joined At',
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

}
