<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "admissions".
 *
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string $email
 * @property string $phone
 * @property int $education_level
 * @property int $educational_programs
 * @property int $created_at
 * @property int $updated_at
 */
class Admissions extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admissions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'surname', 'email', 'phone', 'education_level', 'educational_programs'], 'required'],
            [['education_level', 'educational_programs','created_at','updated_at'], 'integer'],
            [['name', 'surname', 'email', 'phone'], 'string', 'max' => 255],
            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'surname' => 'Surname',
            'email' => 'Email',
            'phone' => 'Phone',
            'education_level' => 'Education Level',
            'educational_programs' => 'Educational Programs',
        ];
    }

}
