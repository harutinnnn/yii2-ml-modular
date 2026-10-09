<?php

namespace common\models;

use common\components\I18n;
use Yii;

/**
 * This is the model class for table "scientific_portfolio".
 *
 * @property int $id
 * @property int $user_id
 * @property string $job_title
 * @property string $type
 * @property string|null $content_type
 * @property string $file_or_url
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 */
class ScientificPortfolio extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const TYPE_ARTICLE = 'article';
    const TYPE_REPORT = 'report';
    const TYPE_RESEARCH = 'research';
    const CONTENT_TYPE_FILE = 'file';
    const CONTENT_TYPE_URL = 'url';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scientific_portfolio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content_type'], 'default', 'value' => 'url'],
            [['user_id', 'job_title', 'type', 'file_or_url'], 'required'],
            [['user_id', 'created_at', 'updated_at'], 'integer'],
            [['type', 'content_type'], 'string'],
            [['job_title', 'file_or_url'], 'string', 'max' => 255],
            ['type', 'in', 'range' => array_keys(self::optsType())],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            ['content_type', 'in', 'range' => array_keys(self::optsContentType())],
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
            'job_title' => 'Job Title',
            'type' => 'Type',
            'content_type' => 'Content Type',
            'file_or_url' => 'File Or Url',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * column content_type ENUM value labels
     * @return string[]
     */
    public static function optsContentType()
    {
        return [
            self::CONTENT_TYPE_URL => I18n::translate(self::CONTENT_TYPE_URL),
            self::CONTENT_TYPE_FILE => I18n::translate(self::CONTENT_TYPE_FILE),
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }


    /**
     * column type ENUM value labels
     * @return string[]
     */
    public static function optsType()
    {
        return [
            self::TYPE_ARTICLE => I18n::translate(self::TYPE_ARTICLE),
            self::TYPE_REPORT => I18n::translate(self::TYPE_REPORT),
            self::TYPE_RESEARCH => I18n::translate(self::TYPE_RESEARCH),
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
    public function isTypeArticle()
    {
        return $this->type === self::TYPE_ARTICLE;
    }

    public function setTypeToArticle()
    {
        $this->type = self::TYPE_ARTICLE;
    }

    /**
     * @return bool
     */
    public function isTypeReport()
    {
        return $this->type === self::TYPE_REPORT;
    }

    public function setTypeToReport()
    {
        $this->type = self::TYPE_REPORT;
    }

    /**
     * @return bool
     */
    public function isTypeResearch()
    {
        return $this->type === self::TYPE_RESEARCH;
    }

    public function setTypeToResearch()
    {
        $this->type = self::TYPE_RESEARCH;
    }
}
