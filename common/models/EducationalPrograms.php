<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $status
 * @property float $duration_by_year
 * @property int $education_level
 * @property int $created_at
 * @property int $updated_at
 *
 * @property EducationalProgramsMl[] $translations
 */
class EducationalPrograms extends ActiveRecord
{
    public const STATUS_PENDING = 0;
    public const STATUS_PUBLISHED = 1;

    public static function tableName(): string
    {
        return '{{%educational_programs}}';
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
            [['status','education_level','duration_by_year','is_remote'], 'required'],
            [['status','education_level','is_remote'], 'integer'],
            [['duration_by_year'], 'number'],
            [['status'], 'in', 'range' => array_keys(self::statusOptions())],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'duration_by_year' => 'Duration by year',
            'education_level' => 'Education level',
            'is_remote' => 'Is remote',
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
        return $this->hasMany(EducationalProgramsMl::class, ['educational_program_id' => 'id'])->indexBy('lang');
    }

    public function getTranslation(string $lang): ?EducationalProgramsMl
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

    public function getEducationalLevel()
    {
        return $this->hasOne(EducationLevels::class, ['education_level' => 'id']);
    }


}
