<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "faculty_education_levels_lcp".
 *
 * @property int $faculty_id
 * @property int $education_level_id
 *
 * @property EducationLevels $educationLevel
 * @property Faculties $faculty
 */
class FacultyEducationLevelsLcp extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'faculty_education_levels_lcp';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['faculty_id', 'education_level_id'], 'required'],
            [['faculty_id', 'education_level_id'], 'integer'],
            [['faculty_id', 'education_level_id'], 'unique', 'targetAttribute' => ['faculty_id', 'education_level_id']],
            [['faculty_id'], 'exist', 'skipOnError' => true, 'targetClass' => Faculties::class, 'targetAttribute' => ['faculty_id' => 'id']],
            [['education_level_id'], 'exist', 'skipOnError' => true, 'targetClass' => EducationLevels::class, 'targetAttribute' => ['education_level_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'faculty_id' => 'Faculty ID',
            'education_level_id' => 'Education Level ID',
        ];
    }

    /**
     * Gets query for [[EducationLevel]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEducationLevel()
    {
        return $this->hasOne(EducationLevels::class, ['id' => 'education_level_id']);
    }

    /**
     * Gets query for [[Faculty]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFaculty()
    {
        return $this->hasOne(Faculties::class, ['id' => 'faculty_id']);
    }

}
