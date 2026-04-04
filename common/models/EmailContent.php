<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property EmailContentMl[] $translations
 */
class EmailContent extends ActiveRecord
{
    public const STATUS_PENDING = 0;
    public const STATUS_PUBLISHED = 1;

    public static function tableName(): string
    {
        return '{{%emailContent}}';
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules(): array
    {
        return [
            [['status'], 'required'],
            [['status'], 'integer'],
            [['status'], 'in', 'range' => array_keys(self::statusOptions())],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PUBLISHED => 'Published',
        ];
    }

    public function getTranslations()
    {
        return $this->hasMany(EmailContentMl::class, ['email_content_id' => 'id'])->indexBy('lang');
    }

    public function getTranslation(string $lang): ?EmailContentMl
    {
        $translations = $this->translations;

        return $translations[$lang] ?? null;
    }

    public function getDisplayTitle(): string
    {
        $defaultLanguage = Language::find()->where(['is_default' => 1])->select('code')->scalar();
        if ($defaultLanguage) {
            $translation = $this->getTranslation($defaultLanguage);
            if ($translation !== null && $translation->title !== '') {
                return $translation->title;
            }
        }

        foreach ($this->translations as $translation) {
            if ($translation->title !== '') {
                return $translation->title;
            }
        }

        return 'Untitled';
    }

    public function getStatusLabel(): string
    {
        return self::statusOptions()[$this->status] ?? 'Unknown';
    }
}
