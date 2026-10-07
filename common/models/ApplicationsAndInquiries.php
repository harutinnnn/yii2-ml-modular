<?php

namespace common\models;

use common\components\I18n;
use Yii;

/**
 * This is the model class for table "applications_and_inquiries".
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $type
 * @property string $subject
 * @property string $description
 * @property int $created_at
 * @property int $updated_at
 */
class ApplicationsAndInquiries extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const TYPE_APPLICATION = 'application';
    const TYPE_REPORT = 'report';
    const TYPE_COMPLAINT = 'complaint';
    const TYPE_SUGGESTION = 'suggestion';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'applications_and_inquiries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type'], 'default', 'value' => null],
            [['user_id', 'subject', 'description'], 'required'],
            [['user_id','created_at','updated_at'], 'integer'],
            [['type'], 'string'],
            [['subject', 'description'], 'string', 'max' => 255],
            ['type', 'in', 'range' => array_keys(self::getTypes())],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'type' => 'Type',
            'subject' => 'Subject',
            'description' => 'Description',
        ];
    }




    public static function getTypes()
    {
        return [
            self::TYPE_APPLICATION => I18n::translate(self::TYPE_APPLICATION),
            self::TYPE_REPORT => I18n::translate(self::TYPE_REPORT),
            self::TYPE_COMPLAINT => I18n::translate(self::TYPE_COMPLAINT),
            self::TYPE_SUGGESTION => I18n::translate(self::TYPE_SUGGESTION),

        ];
    }
}
