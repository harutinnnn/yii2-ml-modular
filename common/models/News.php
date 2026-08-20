<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $status
 * @property int $category_id
 * @property string|null $image
 * @property int $created_at
 * @property int $updated_at
 *
 * @property NewsMl[] $translations
 */
class News extends ActiveRecord
{
    public const STATUS_PENDING = 0;
    public const STATUS_PUBLISHED = 1;

    public static function tableName(): string
    {
        return '{{%news}}';
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
            [['status','category_id'], 'required'],
            [['status','category_id'], 'integer'],
            [['image'], 'string', 'max' => 255],
            [['status'], 'in', 'range' => array_keys(self::statusOptions())],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'category_id' => 'Category',
            'image' => 'Image',
            'date' => 'Date',
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
        return $this->hasMany(NewsMl::class, ['news_id' => 'id'])->indexBy('lang');
    }

    public function getTranslation(string $lang): ?NewsMl
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
