<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $key
 * @property int $status
 * @property string $type
 * @property int $created_at
 * @property int $updated_at
 *
 * @property FrontendLanguageMl[] $translations
 */
class FrontendLanguage extends ActiveRecord
{
    public const STATUS_PENDING = 0;
    public const STATUS_PUBLISHED = 1;

    public const TYPE_CONTENT = 'content';
    public const TYPE_LABEL = 'label';

    public static function tableName(): string
    {
        return '{{%frontend_languages}}';
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
            [['key', 'status', 'type'], 'required'],
            [['status'], 'integer'],
            [['status'], 'in', 'range' => array_keys(self::statusOptions())],
            [['key'], 'string', 'max' => 255],
            [['key'], 'unique'],
            [['type'], 'in', 'range' => array_keys(self::typeOptions())],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'key' => 'Key',
            'status' => 'Status',
            'type' => 'Type',
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

    public static function typeOptions(): array
    {
        return [
            self::TYPE_CONTENT => 'Content',
            self::TYPE_LABEL => 'Label',
        ];
    }

    public function getTranslations()
    {
        return $this->hasMany(FrontendLanguageMl::class, ['frontend_language_id' => 'id'])->indexBy('lang');
    }

    public function getTranslation(string $lang): ?FrontendLanguageMl
    {
        $translations = $this->translations;

        return $translations[$lang] ?? null;
    }

    public function getDisplayKey(): string
    {
        return $this->key ?: 'No key';
    }

    public function getStatusLabel(): string
    {
        return self::statusOptions()[$this->status] ?? 'Unknown';
    }

    public function getTypeLabel(): string
    {
        return self::typeOptions()[$this->type] ?? 'Unknown';
    }
}
