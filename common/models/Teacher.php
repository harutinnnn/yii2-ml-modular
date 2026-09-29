<?php

namespace common\models;

use Symfony\Component\VarDumper\Cloner\Data;
use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $verification_token
 */
class Teacher extends \yii\db\ActiveRecord
{

    public const STATUS_DELETED = 0;
    public const STATUS_INACTIVE = 9;
    public const STATUS_ACTIVE = 10;
    public const STATUS_REJECTED = 2;

    public $first_name;
    public $last_name;
    public $middle_name;
    public $phone;
    public $teacher_academic_degree;
    public $teacher_position;
    public $faculty;
    public $chair;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'default', 'value' => 10],
            [['first_name', 'middle_name', 'last_name', 'teacher_academic_degree', 'teacher_position', 'phone', 'university_email',], 'string'],
            [['status', 'created_at', 'faculty', 'chair', 'updated_at'], 'integer'],
            [['password_reset_token', 'email', 'verification_token'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [['email'], 'email'],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'auth_key' => 'Auth Key',
            'password' => 'Password',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'verification_token' => 'Verification Token',
        ];
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_INACTIVE => 'Pending',
            self::STATUS_ACTIVE => 'Published',
            self::STATUS_REJECTED => 'Rejected',
        ];
    }

    /**
     * Sends confirmation email to user
     * @param \common\models\User $user user model to with email should be send
     * @return bool whether the email was sent
     */


    public function getAdditional()
    {
        return $this->hasOne(UserAdditionalData::class, ['user_id' => 'id']);
    }

    public function getFaculty()
    {
        return $this->hasOne(UserFacultyChairLcp::class, ['user_id' => 'id']);
    }

    public function getStatusLabel(): string
    {
        return self::statusOptions()[$this->status] ?? 'Unknown';
    }
}
