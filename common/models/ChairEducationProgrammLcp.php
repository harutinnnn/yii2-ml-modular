<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "chair_education_programm_lcp".
 *
 * @property int $chair_id
 * @property int $education_programm_id
 *
 * @property Chairs $chair
 * @property EducationalPrograms $educationProgramm
 */
class ChairEducationProgrammLcp extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chair_education_programm_lcp';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chair_id', 'education_programm_id'], 'required'],
            [['chair_id', 'education_programm_id'], 'integer'],
            [['chair_id', 'education_programm_id'], 'unique', 'targetAttribute' => ['chair_id', 'education_programm_id']],
            [['chair_id'], 'exist', 'skipOnError' => true, 'targetClass' => Chairs::class, 'targetAttribute' => ['chair_id' => 'id']],
            [['education_programm_id'], 'exist', 'skipOnError' => true, 'targetClass' => EducationalPrograms::class, 'targetAttribute' => ['education_programm_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'chair_id' => 'Chair ID',
            'education_programm_id' => 'Education Programm ID',
        ];
    }

    /**
     * Gets query for [[Chair]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChair()
    {
        return $this->hasOne(Chairs::class, ['id' => 'chair_id']);
    }

    /**
     * Gets query for [[EducationProgramm]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEducationProgramm()
    {
        return $this->hasOne(EducationalPrograms::class, ['id' => 'education_programm_id']);
    }

}
