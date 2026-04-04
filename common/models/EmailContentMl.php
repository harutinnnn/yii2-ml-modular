<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $email_content_id
 * @property string $lang
 * @property string $title
 * @property string $text
 *
 * @property EmailContent $emailContent
 */
class EmailContentMl extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%emailContent_ml}}';
    }

    public function rules(): array
    {
        return [
            [['email_content_id', 'lang', 'title', 'text'], 'required'],
            [['email_content_id'], 'integer'],
            [['text'], 'string'],
            [['lang'], 'string', 'max' => 8],
            [['title'], 'string', 'max' => 255],
            [['email_content_id', 'lang'], 'unique', 'targetAttribute' => ['email_content_id', 'lang']],
            [['email_content_id'], 'exist', 'targetClass' => EmailContent::class, 'targetAttribute' => ['email_content_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'email_content_id' => 'Email Content',
            'lang' => 'Language',
            'title' => 'Title',
            'text' => 'Text',
        ];
    }

    public function getEmailContent()
    {
        return $this->hasOne(EmailContent::class, ['id' => 'email_content_id']);
    }
}
