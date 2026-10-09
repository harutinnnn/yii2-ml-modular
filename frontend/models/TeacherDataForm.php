<?php

namespace frontend\models;

use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class TeacherDataForm extends Model
{
    public $id;
    public $first_name;
    public $last_name;
    public $middle_name;
    public $dob;
    public $passport_details;
    public $phone;
    public $email;
    public $contact_email;
    public $teacher_academic_degree;
    public $teacher_position;

    public $teacher_language_proficiency;
    public $teacher_work_experience;
    public $teacher_certificates;
    public $teacher_subjects_taught;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'first_name', 'last_name', 'middle_name', 'dob', 'passport_details', 'phone', 'email',
                    'contact_email', 'teacher_position', 'teacher_academic_degree',
                    'teacher_language_proficiency', 'teacher_work_experience', 'teacher_certificates', 'teacher_subjects_taught'
                ], 'required'
            ],
            [
                [
                    'first_name', 'last_name', 'middle_name', 'phone', 'passport_details', 'email',
                    'teacher_position', 'teacher_academic_degree',
                    'teacher_language_proficiency', 'teacher_work_experience', 'teacher_certificates', 'teacher_subjects_taught'
                ], 'string'],
            [
                ['teacher_language_proficiency', 'teacher_work_experience', 'teacher_certificates', 'teacher_subjects_taught'], 'string', 'max' => 255
            ],
            [['dob'], 'date', 'format' => 'php:Y-m-d'],
            ['email', 'email'],
            ['email', 'unique',
                'targetClass' => \common\models\User::class,
                'targetAttribute' => 'email',
                'message' => 'This email is already registered.',
                'filter' => function ($query) {
                    if ($this->id) {
                        $query->andWhere(['<>', 'id', $this->id]);
                    }
                },
            ],
            ['contact_email', 'email'],
            ['contact_email', 'unique',
                'targetClass' => \common\models\User::class,
                'targetAttribute' => 'contact_email',
                'message' => 'This contact email is already registered.',
                'filter' => function ($query) {
                    if ($this->id) {
                        $query->andWhere(['<>', 'id', $this->id]);
                    }
                },
            ],
        ];
    }
}
