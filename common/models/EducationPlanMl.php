<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $educational_plan_id
 * @property string $lang
 * @property string $title
 * @property string $text
 *
 * @property EducationPlan $education_plan
 */
class EducationPlanMl extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%education_plan_ml}}';
    }

    public function rules(): array
    {
        return [
            [['educational_plan_id', 'lang', 'title', 'text'], 'required'],
            [['educational_plan_id'], 'integer'],
            [['lang'], 'string', 'max' => 8],
            [['title'], 'string', 'max' => 255],
            [['text'], 'string'],
            [['educational_plan_id', 'lang'], 'unique', 'targetAttribute' => ['educational_plan_id', 'lang']],
            [['educational_plan_id'], 'exist', 'targetClass' => EducationPlan::class, 'targetAttribute' => ['educational_plan_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'educational_plan_id' => 'Educational plan',
            'lang' => 'Language',
            'title' => 'Title',
            'text' => 'Text',
        ];
    }

    public function getEducationPlan()
    {
        return $this->hasOne(EducationPlan::class, ['id' => 'educational_plan_id']);
    }
}
