<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "statuses".
 *
 * @property int $id
 * @property int $status
 *
 * @property StatusesMl[] $statusesMls
 * @property UserAdditionalData[] $userAdditionalDatas
 *
 * @property StatusesMl[] $translations
 */
class Statuses extends \yii\db\ActiveRecord
{

    public const STATUS_PENDING = 0;
    public const STATUS_PUBLISHED = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%statuses}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'default', 'value' => 0],
            [['status'], 'integer'],
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
        return $this->hasMany(StatusesMl::class, ['status_id' => 'id'])->indexBy('lang');
    }

    public function getTranslation(string $lang): ?StatusesMl
    {
        $translations = $this->translations;

        return $translations[$lang] ?? null;
    }

    public function getUserAdditionalDatas()
    {
        return $this->hasMany(UserAdditionalData::class, ['student_status' => 'id']);
    }

    public function getStatusLabel(): string
    {
        return self::statusOptions()[$this->status] ?? 'Unknown';
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

}
