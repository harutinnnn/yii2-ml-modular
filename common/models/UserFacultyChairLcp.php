<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_faculty_chair_lcp".
 *
 * @property int $faculty_id
 * @property int $chair_id
 * @property int $user_id
 *
 * @property Chairs $chair
 * @property Faculties $faculty
 * @property User $user
 */
class UserFacultyChairLcp extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_faculty_chair_lcp';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['faculty_id', 'chair_id', 'user_id'], 'required'],
            [['faculty_id', 'chair_id', 'user_id'], 'integer'],
            [['faculty_id', 'chair_id'], 'unique', 'targetAttribute' => ['faculty_id', 'chair_id']],
            [['faculty_id'], 'unique'],
            [['faculty_id'], 'exist', 'skipOnError' => true, 'targetClass' => Faculties::class, 'targetAttribute' => ['faculty_id' => 'id']],
            [['chair_id'], 'exist', 'skipOnError' => true, 'targetClass' => Chairs::class, 'targetAttribute' => ['chair_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'faculty_id' => 'Faculty ID',
            'chair_id' => 'Chair ID',
            'user_id' => 'User ID',
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
     * Gets query for [[Faculty]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFaculty()
    {
        return $this->hasOne(Faculties::class, ['id' => 'faculty_id']);
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
