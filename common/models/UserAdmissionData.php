<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_admission_data".
 *
 * @property int $id
 * @property int $user_id
 * @property int $education_level
 * @property int $educational_programs
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 */
class UserAdmissionData extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_admission_data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'education_level', 'educational_programs'], 'required'],
            [['user_id', 'education_level', 'educational_programs'], 'integer'],
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
            'education_level' => 'Education Level',
            'educational_programs' => 'Educational Programs',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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


    public function beforeSave($insert)
    {

        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($insert) {
            $this->created_at = time();
            $this->updated_at = time();
        } else {
            $this->updated_at = time();
        }

        return true;
    }

}
