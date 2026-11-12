<?php

namespace common\models;

use common\components\StatusList;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property NewsCategoriesMl[] $translations
 */
class NewsCategories extends ActiveRecord
{

    public static function tableName(): string
    {
        return '{{%news_categories}}';
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
            'date' => 'Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function statusOptions(): array
    {

        return [
            StatusList::STATUS_PENDING => 'Pending',
            StatusList::STATUS_PUBLISHED => 'Published',
        ];
    }

    public function getTranslations()
    {
        return $this->hasMany(NewsCategoriesMl::class, ['news_categories_id' => 'id'])->indexBy('lang');
    }

    public function getTranslation(string $lang): ?NewsCategoriesMl
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
