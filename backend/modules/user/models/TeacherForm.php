<?php

namespace backend\modules\user\models;

use common\components\UserRoles;
use common\models\Chairs;
use common\models\EducationalPrograms;
use common\models\EducationLevels;
use common\models\Faculties;
use common\models\Teacher;
use common\models\UserAdditionalData;
use common\models\UserAdmissionData;
use common\models\UserFacultyChairLcp;
use Yii;

/**
 * This is the model class for table "user".
 *
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $phone
 * @property string $email
 * @property string $university_email
 * @property string $teacher_academic_degree
 * @property string $teacher_position
 * @property int $faculty
 * @property int $chair
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class TeacherForm extends \yii\base\Model
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    public ?Teacher $user = null;
    public ?UserAdditionalData $userAdditionalData = null;
    public ?UserFacultyChairLcp $userFacultyChairLcp = null;

    public $id;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $phone;
    public $email;
    public $university_email;
    public $teacher_academic_degree;
    public $teacher_position;
    public $faculty;
    public $chair;
    public $status;
    public $created_at;
    public $updated_at;
    public $verification_token;

    public $faculty_title;
    public $chair_title;

    public const STATUS_INACTIVE = 9;
    public const STATUS_ACTIVE = 10;


    public function __construct(?Teacher $user = null, $config = [])
    {

        $this->user = $user;
        parent::__construct($config);


        if ($this->user !== null) {

            $this->id = (int)$this->user->id;
            $this->first_name = (string)$user->additional->first_name;
            $this->middle_name = (string)$user->additional->middle_name;
            $this->last_name = (string)$user->additional->last_name ?? "";
            $this->phone = (string)$user->additional->phone ?? "";
            $this->email = (string)$user->email;
            $this->university_email = (string)$user->university_email;
            $this->teacher_academic_degree = (string)$user->additional->teacher_academic_degree ?? "";
            $this->teacher_position = (string)$user->additional->teacher_position ?? "";
            $this->faculty = (int)$user->additional->faculty;
            $this->chair = (int)$user->additional->chair;
            $this->status = (int)$this->user->status;
            $this->created_at = $user->created_at;
            $this->updated_at = $user->updated_at;

            if ($user->additional) {
                $this->faculty = (int)$user->additional->faculty ?? 0;
                $this->chair = (int)$user->additional->chair ?? 0;

                $faculty = Faculties::find()->where(['id' => $this->faculty])->one();
                $this->faculty_title = $faculty->getDisplayTitle();

                $chair = Chairs::find()->where(['id' => $this->chair])->one();
                $this->chair_title = $chair->getDisplayTitle();

            } else {
                $this->faculty = 0;
                $this->chair = 0;
            }

            $this->userAdditionalData = $user->additional;
        }
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_CREATE] = [
            'first_name',
            'middle_name',
            'last_name',
            'phone',
            'email',
            'university_email',
            'teacher_academic_degree',
            'teacher_position',
            'faculty',
            'chair',
            'status',
        ];

        $scenarios[self::SCENARIO_UPDATE] = [
            'first_name',
            'middle_name',
            'last_name',
            'phone',
            'email',
            'university_email',
            'teacher_academic_degree',
            'teacher_position',
            'faculty',
            'chair',
            'status',
        ];

        return $scenarios;
    }

    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['status'], 'default', 'value' => 10],
            [['first_name', 'middle_name', 'last_name', 'teacher_academic_degree', 'teacher_position', 'faculty', 'chair', 'phone', 'university_email',], 'required'],
            [['first_name', 'middle_name', 'last_name', 'teacher_academic_degree', 'teacher_position', 'phone', 'university_email',], 'string'],
            [['status', 'created_at', 'faculty', 'chair', 'updated_at'], 'integer'],

            [['email'], 'string', 'max' => 255, 'on' => self::SCENARIO_CREATE],
            [['email'], 'required', 'on' => self::SCENARIO_CREATE],
            ['email', 'email', 'on' => self::SCENARIO_CREATE],
            ['university_email', 'email'],

            [
                'email',
                'unique',
                'targetClass' => Teacher::class,
                'targetAttribute' => 'email',
                'message' => 'This email has already been taken.',
                'on' => self::SCENARIO_CREATE
            ],
            [
                'university_email',
                'unique',
                'targetClass' =>Teacher::class,
                'targetAttribute' => 'university_email',
                'filter' => function ($query) {
                    if ($this->id) {
                        $query->andWhere(['<>', 'id', $this->id]);
                    }
                },
                'message' => 'This email has already been taken.',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'first_name' => 'First name',
            'middle_name' => 'Middle name',
            'last_name' => 'Last name',
            'phone' => 'Phone',
            'email' => 'Email',
            'university_email' => 'University email',
            'teacher_academic_degree' => 'Academic degree',
            'teacher_position' => 'Teacher position',
            'faculty' => 'Faculty',
            'chair' => 'Chair',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_INACTIVE => 'Pending',
            self::STATUS_ACTIVE => 'Published',
        ];
    }

    public function createTeacher(): ?bool
    {

        if (!$this->validate()) {
            return null;
        }


        $transaction = Yii::$app->db->beginTransaction();

        try {

            $pass = substr(md5(sha1(microtime())), 0, 8);

            $user = $this->user ?? new Teacher();
            $user->status = $this->status;
            $user->email = $this->email;
            $user->university_email = $this->university_email;
            $passHash = Yii::$app->security->generatePasswordHash($pass);
            $user->password_hash = $passHash;
            $user->auth_key = Yii::$app->security->generateRandomString();
            $user->created_at = time();
            $user->updated_at = time();

            if ($user->save()) {


                $auth = Yii::$app->authManager;
                $role = $auth->getRole(UserRoles::TEACHER);
                $auth->assign($role, $user->id);


                $userAdditionalData = new UserAdditionalData();
                $userAdditionalData->first_name = $this->first_name;
                $userAdditionalData->middle_name = $this->middle_name;
                $userAdditionalData->last_name = $this->last_name;
                $userAdditionalData->teacher_academic_degree = $this->teacher_academic_degree;
                $userAdditionalData->teacher_position = $this->teacher_position;
                $userAdditionalData->faculty = $this->faculty;
                $userAdditionalData->chair = $this->chair;
                $userAdditionalData->phone = $this->phone;
                $userAdditionalData->user_id = $user->id;

                if (!$userAdditionalData->save()) {

                    foreach ($userAdditionalData->getErrors() as $attribute => $errors) {
                        foreach ($errors as $error) {
                            $this->addError($attribute, $error);
                        }
                    }

                    $transaction->rollBack();

                    return false;

                }

            } else {
                foreach ($user->getErrors() as $attribute => $errors) {
                    foreach ($errors as $error) {
                        $this->addError($attribute, $error);
                    }
                }

                $transaction->rollBack();

                return false;
            }


            $this->sendEmail($user);
            $this->id = $user->id;
            $transaction->commit();

            return true;

        } catch (\Throwable $e) {

            $transaction->rollBack();
            throw $e; // or handle the error appropriately
        }
    }

    public function updateTeacher()
    {
        if (!$this->validate()) {
            return null;
        }


        $transaction = Yii::$app->db->beginTransaction();

        try {

            $user = $this->user ?? new Teacher();
            $user->status = $this->status;
            $user->updated_at = time();



            if ($user->save()) {

                $userAdditionalData = $this->userAdditionalData ?? new UserAdditionalData();
                $userAdditionalData->first_name = $this->first_name;
                $userAdditionalData->middle_name = $this->middle_name;
                $userAdditionalData->last_name = $this->last_name;
                $userAdditionalData->teacher_academic_degree = $this->teacher_academic_degree;
                $userAdditionalData->teacher_position = $this->teacher_position;
                $userAdditionalData->faculty = $this->faculty;
                $userAdditionalData->chair = $this->chair;
                $userAdditionalData->phone = $this->phone;
                $userAdditionalData->user_id = $user->id;


                if (!$userAdditionalData->save()) {



                    foreach ($userAdditionalData->getErrors() as $attribute => $errors) {
                        foreach ($errors as $error) {
                            $this->addError($attribute, $error);
                        }
                    }

                    $transaction->rollBack();

                    return false;
                }

                if ($this->status == Teacher::STATUS_ACTIVE) {
                    //TODO email


                } else if ($this->status == Teacher::STATUS_REJECTED) {
                    //TODO email


                }

            } else {

                foreach ($user->getErrors() as $attribute => $errors) {
                    foreach ($errors as $error) {
                        $this->addError($attribute, $error);
                    }
                }

                $transaction->rollBack();

                return false;
            }


            $this->id = $user->id;
            $transaction->commit();
            return true;

        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e; // or handle the error appropriately
        }
    }

    public function sendEmail($user): bool
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
