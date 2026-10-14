<?php

namespace common\models;

use common\components\StatusList;
use Yii;

/**
 * This is the model class for table "conversations".
 *
 * @property int $id
 * @property int $status
 * @property string $type
 * @property string $title
 * @property int $moderator
 * @property int $created_by
 * @property string|null $created_at
 *
 * @property ConversationMembers[] $conversationMembers
 * @property Messages[] $messages
 * @property User $moderator_
 * @property User $creator_
 */
class Conversations extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const TYPE_PRIVATE = 'private';
    const TYPE_GROUP = 'group';


    const ACTION_ASSIGN = 'assign';
    const ACTION_REMOVE = 'remove';

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
            [['type'], 'default', 'value' => self::TYPE_GROUP],
            [['moderator'], 'default', 'value' => 0],
            [['type'], 'string'],
            [['status','moderator', 'created_by'], 'integer'],
            [['title', 'created_by'], 'required'],
            [['created_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            ['type', 'in', 'range' => array_keys(self::optsType())],
            [['moderator'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['moderator' => 'id']],
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
            'type' => 'Type',
            'title' => 'Title',
            'moderator' => 'Moderator',
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

    public function getCreator_()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    public function getModerator_()
    {
        return $this->hasOne(User::class, ['id' => 'moderator']);
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


    public static function statusOptions(): array
    {
        return [
            StatusList::STATUS_ACTIVE => 'Published',
            StatusList::STATUS_PENDING => 'Pending',
        ];
    }

    public function getStatusLabel(): string
    {
        return self::statusOptions()[$this->status] ?? 'Unknown';
    }
}
