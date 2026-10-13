<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "message_files".
 *
 * @property int $id
 * @property int $message_id
 * @property string $name
 * @property string $type
 * @property string $file
 * @property string|null $created_at
 *
 * @property Messages $message
 */
class MessageFiles extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message_files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message_id', 'name', 'type', 'file'], 'required'],
            [['message_id'], 'integer'],
            [['created_at'], 'safe'],
            [['name', 'type', 'file'], 'string', 'max' => 255],
            [['message_id'], 'exist', 'skipOnError' => true, 'targetClass' => Messages::class, 'targetAttribute' => ['message_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'message_id' => 'Message ID',
            'name' => 'Name',
            'type' => 'Type',
            'file' => 'File',
            'created_at' => 'Created At',
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
