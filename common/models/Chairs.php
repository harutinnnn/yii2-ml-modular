<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $status
 * @property int $faculty_id
 * @property int $pos
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ChairsMl[] $translations
 */
class Chairs extends ActiveRecord
{
    public const STATUS_PENDING = 0;
    public const STATUS_PUBLISHED = 1;

    public static function tableName(): string
    {
        return '{{%chairs}}';
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
            [['status','pos','faculty_id'], 'required'],
            [['status','pos','faculty_id'], 'integer'],
            [['status'], 'in', 'range' => array_keys(self::statusOptions())],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'faculty_id' => 'Faculty',
            'pos' => 'Position',
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
        return $this->hasMany(ChairsMl::class, ['chair_id' => 'id'])->indexBy('lang');
    }

    public function getTranslation(string $lang): ?ChairsMl
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


    public static function getFalcultiesKeyVal($id)
    {

        $defaultLanguage = Language::find()->where(['is_default' => 1])->select('code')->scalar();
        $faculties = Chairs::find()
            ->alias('ec')
            ->joinWith('translations ect')
            ->groupBy('ec.id')
            ->where(['ec.faculty_id' => $id])
            ->all();

        $facultiesTmp = [];
        foreach ($faculties as $faculty) {
            $facultiesTmp[$faculty->id] = $faculty->translations[$defaultLanguage]->title;
        }
        return $facultiesTmp;
    }


}
