<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "chair_staff_lcp".
 *
 * @property int $chair_id
 * @property int $user_id
 *
 * @property Chairs $chair
 * @property User $user
 */
class ChairStaffLcp extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chair_staff_lcp';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chair_id', 'user_id'], 'required'],
            [['chair_id', 'user_id'], 'integer'],
            [['chair_id', 'user_id'], 'unique', 'targetAttribute' => ['chair_id', 'user_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}
