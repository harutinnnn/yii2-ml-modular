<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "conversations".
 *
 * @property int $id
 * @property string $type
 * @property string|null $title
 * @property int $created_by
 * @property string|null $created_at
 *
 * @property ConversationMembers[] $conversationMembers
 * @property Messages[] $messages
 */
class Conversations extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const TYPE_PRIVATE = 'private';
    const TYPE_GROUP = 'group';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'conversations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'default', 'value' => null],
            [['type'], 'default', 'value' => self::TYPE_GROUP],
            [['type'], 'string'],
            [['created_by'], 'integer'],
            [['created_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            ['type', 'in', 'range' => array_keys(self::optsType())],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'title' => 'Title',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[ConversationMembers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConversationMembers()
    {
        return $this->hasMany(ConversationMembers::class, ['conversation_id' => 'id']);
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Messages::class, ['conversation_id' => 'id']);
    }

    /**
     * column type ENUM value labels
     * @return string[]
     */
    public static function optsType()
    {
        return [
            self::TYPE_GROUP => self::TYPE_GROUP,
            self::TYPE_PRIVATE => self::TYPE_PRIVATE,
        ];
    }

    /**
     * @return string
     */
    public function displayType()
    {
        return self::optsType()[$this->type];
    }

    /**
     * @return bool
     */
    public function isTypePrivate()
    {
        return $this->type === self::TYPE_PRIVATE;
    }

    public function setTypeToPrivate()
    {
        $this->type = self::TYPE_PRIVATE;
    }

    /**
     * @return bool
     */
    public function isTypeGroup()
    {
        return $this->type === self::TYPE_GROUP;
    }

    public function setTypeToGroup()
    {
        $this->type = self::TYPE_GROUP;
    }

}
