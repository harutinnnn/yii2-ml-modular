<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $status
 * @property int $pos
 * @property int $educational_program_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property EducationPlanMl[] $translations
 */
class EducationPlan extends ActiveRecord
{
    public const STATUS_PENDING = 0;
    public const STATUS_PUBLISHED = 1;

    public static function tableName(): string
    {
        return '{{%education_plan}}';
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
            [['status','pos','educational_program_id'], 'required'],
            [['status','pos','educational_program_id'], 'integer'],
            [['status'], 'in', 'range' => array_keys(self::statusOptions())],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'pos' => 'Position',
            'educational_program_id' => 'Educational program',
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
        return $this->hasMany(EducationPlanMl::class, ['educational_plan_id' => 'id'])->indexBy('lang');
    }

    public function getTranslation(string $lang): ?EducationPlanMl
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

    /**
     * Gets query for [[EducationalPrograms]].
     * @return \yii\db\ActiveQuery
     */
    public function getEducationPlan()
    {
        return $this->hasMany(EducationPlan::class, ['education_plan' => 'id']);
    }
}
