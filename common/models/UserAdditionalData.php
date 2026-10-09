<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_additional_data".
 *
 * @property int $id
 * @property int $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $middle_name
 * @property string|null $dob
 * @property string|null $passport_details
 * @property string|null $phone
 * @property string|null $faculty
 * @property string|null $chair
 * @property string|null $profession
 * @property int|null $course
 * @property string|null $group
 * @property int|null $student_status
 * @property string|null $teacher_academic_degree
 * @property string|null $teacher_position
 * @property string|null $student_id
 * @property int|null $student_number
 * @property int|null $year
 *
 *
 * @property string|null $teacher_language_proficiency
 * @property string|null $teacher_work_experience
 * @property string|null $teacher_certificates
 * @property string|null $teacher_subjects_taught
 *
 * @property Statuses $studentStatus
 * @property User $user
 */
class UserAdditionalData extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_additional_data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'middle_name', 'dob', 'passport_details', 'phone', 'faculty',
                'chair', 'profession', 'course', 'group', 'student_status', 'teacher_academic_degree',
                'teacher_position', 'student_id'], 'default', 'value' => null],
            [['user_id', 'first_name', 'last_name'], 'required'],
            [['user_id', 'student_status', 'course'], 'integer'],
            [['dob'], 'safe'],
            [[
                'first_name', 'last_name', 'middle_name', 'passport_details', 'faculty',
                'teacher_language_proficiency', 'teacher_work_experience', 'teacher_certificates', 'teacher_subjects_taught',
            ], 'string', 'max' => 100],
            [['phone'], 'string', 'max' => 25],
            [['chair', 'profession'], 'string', 'max' => 150],
            [['group'], 'string', 'max' => 255],
            [['student_status'], 'exist', 'skipOnError' => true, 'targetClass' => Statuses::class, 'targetAttribute' => ['student_status' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'middle_name' => 'Middle Name',
            'dob' => 'Dob',
            'passport_details' => 'Passport Details',
            'phone' => 'Phone',
            'faculty' => 'Faculty',
            'chair' => 'Chair',
            'profession' => 'Profession',
            'course' => 'Course',
            'group' => 'Group',
            'student_status' => 'Student Status',
            'teacher_academic_degree' => 'Academic degree',
            'teacher_position' => 'Position',
            'student_id' => 'Student ID',
            'student_number' => 'Student Number',
            'year' => 'Year',
        ];
    }

    /**
     * Gets query for [[StudentStatus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudentStatus()
    {
        return $this->hasOne(Statuses::class, ['id' => 'student_status']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}
