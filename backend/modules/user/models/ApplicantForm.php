<?php

namespace backend\modules\user\models;

use common\components\StatusList;
use common\components\UserRoles;
use common\models\Applicant;
use common\models\Chairs;
use common\models\EducationalPrograms;
use common\models\EducationLevels;
use common\models\Faculties;
use common\models\UserAdditionalData;
use common\models\UserAdmissionData;
use common\models\UserFacultyChairLcp;
use PHPUnit\Framework\Error;
use Yii;
use yii\base\Model;

/**
 * This is the model class for table "user".
 *
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class ApplicantForm extends \yii\base\Model
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    public ?User $user = null;
    public ?UserAdditionalData $userAdditionalData = null;
    public ?UserFacultyChairLcp $userFacultyChairLcp = null;

    public $id;
    public $status;
    public $email;
    public $university_email;
    public $phone;
    public $education_level;
    public $educational_programs;
    public $faculty;
    public $chair;
    public $first_name;
    public $last_name;
    public $dob;
    public $created_at;
    public $updated_at;
    public $verification_token;

    public $education_level_titile;
    public $educational_programs_title;

    public const STATUS_INACTIVE = 9;
    public const STATUS_ACTIVE = 10;


    public $faculty_title;
    public $chair_title;

    public function __construct(?User $user = null, $config = [])
    {

        $this->user = $user;
        parent::__construct($config);


        if ($this->user !== null) {
            $this->id = (int)$this->user->id;
            $this->status = (int)$this->user->status;
            $this->first_name = (string)$user->additional->first_name;
            $this->last_name = (string)$user->additional->last_name ?? "";
            $this->phone = (string)$user->additional->phone ?? "";
            $this->email = (string)$user->email;
            $this->university_email = (string)$user->university_email;
            $this->created_at = $user->created_at;

            if ($user->userAdmissionData) {
                $this->education_level = (int)$user->userAdmissionData->education_level ?? 0;
                $this->educational_programs = (int)$user->userAdmissionData->educational_programs ?? 0;

                $educationLevels = EducationLevels::find()->where(['id' => $this->education_level])->one();
                $this->education_level_titile = $educationLevels->getDisplayTitle();

                $educationalPrograms = EducationalPrograms::find()->where(['id' => $this->educational_programs])->one();
                $this->educational_programs_title = $educationalPrograms->getDisplayTitle();

                $this->faculty = (int)$user->additional->faculty ?? 0;
                $this->chair = (int)$user->additional->chair ?? 0;

                $faculty = Faculties::find()->where(['id' => $this->faculty])->one();
                if ($faculty) {
                    $this->faculty_title = $faculty->getDisplayTitle();
                }

                $chair = Chairs::find()->where(['id' => $this->chair])->one();
                if ($chair) {
                    $this->chair_title = $chair->getDisplayTitle();
                }


            } else {
                $this->education_level = 0;
                $this->educational_programs = 0;

                $this->faculty = 0;
                $this->chair = 0;
            }

            if ($user->additional) {
                $this->dob = $user->additional->dob;
            } else {
                $this->dob = date("Y-m-d");
            }

            $this->userAdditionalData = $user->additional;
            $this->userFacultyChairLcp = $user->faculty;
        }
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_CREATE] = [
            'email',
            'university_email',
            'phone',
            'status',
            'first_name',
            'last_name',
            'education_level',
            'educational_programs',
            'faculty',
            'chair',
            'dob'
        ];

        $scenarios[self::SCENARIO_UPDATE] = [
            'status',
            'phone',
            'first_name',
            'last_name',
            'education_level',
            'educational_programs',
            'university_email',
            'faculty',
            'chair',
            'dob'
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
            [['first_name', 'last_name', 'education_level', 'educational_programs', 'phone', 'university_email', 'dob', 'faculty', 'chair'], 'required'],
            [['first_name', 'last_name', 'phone', 'university_email'], 'string'],
            [['dob'], 'date', 'format' => 'php:Y-m-d'],
            [['status', 'education_level', 'educational_programs', 'faculty', 'chair', 'created_at', 'updated_at'], 'integer'],

            [['email'], 'string', 'max' => 255, 'on' => self::SCENARIO_CREATE],
            [['email'], 'required', 'on' => self::SCENARIO_CREATE],
            ['email', 'email', 'on' => self::SCENARIO_CREATE],
            ['university_email', 'email'],

            [
                'email',
                'unique',
                'targetClass' => Applicant::class,
                'targetAttribute' => 'email',
                'message' => 'This email has already been taken.',
                'on' => self::SCENARIO_CREATE
            ],

            [
                'university_email',
                'unique',
                'targetClass' => Applicant::class,
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
            'id' => 'ID',
            'education_level' => 'Education level',
            'educational_programs' => 'Educational_program',
            'faculty' => 'Faculty',
            'chair' => 'Chair',
            'email' => 'Email',
            'university_email' => 'University email',
            'phone' => 'Phone',
            'status' => 'Status',
            'first_name' => 'First name',
            'dob' => 'Date of birth',
            'last_name' => 'Last name',
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

    public function sendRejectEmail($user, $userAdditionalData): bool
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'applicant/applicantRejectEmail-html', 'text' => 'applicant/applicantRejectEmail-text'],
                ['user' => $user, 'userAdditionalData' => $userAdditionalData]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }

    public function applicantActivationEmail($user, $userAdditionalData, $pass): bool
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'applicant/applicantActivateEmail-html', 'text' => 'applicant/applicantActivateEmail-text'],
                ['user' => $user, 'userAdditionalData' => $userAdditionalData, 'pass' => $pass]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }


    public function registerApplicant(): ?bool
    {

        if (!$this->validate()) {
            return null;
        }


        $transaction = Yii::$app->db->beginTransaction();

        try {

            $pass = substr(md5(sha1(microtime())), 0, 8);

            $user = $this->user ?? new Applicant();
            $user->status = $this->status;
            $user->email = $this->email;
            $user->university_email = $this->university_email;
            $user->password = $pass;
            $user->auth_key = Yii::$app->security->generateRandomString();

            if ($user->save()) {

                $auth = Yii::$app->authManager;

                $role = $auth->getRole(UserRoles::APPLICANT);
                $auth->assign($role, $user->id);


                $userAdditionalData = new UserAdditionalData();
                $userAdditionalData->first_name = $this->first_name;
                $userAdditionalData->last_name = $this->last_name;
                $userAdditionalData->phone = $this->phone;
                $userAdditionalData->user_id = $user->id;
                $userAdditionalData->faculty = $this->faculty;
                $userAdditionalData->chair = $this->chair;
                $userAdditionalData->dob = $this->dob;



                if ($userAdditionalData->save()) {

                    $userAdmissionData = new UserAdmissionData();
                    $userAdmissionData->education_level = $this->education_level ?? 0;
                    $userAdmissionData->educational_programs = $this->educational_programs ?? 0;
                    $userAdmissionData->user_id = $user->id;

                    if (!$userAdmissionData->save()) {
                        foreach ($userAdmissionData->getErrors() as $attribute => $errors) {
                            foreach ($errors as $error) {
                                $this->addError($attribute, $error);
                            }
                        }

                        $transaction->rollBack();

                        return false;
                    }

                } else {

                    foreach ($userAdditionalData->getErrors() as $attribute => $errors) {
                        foreach ($errors as $error) {
                            $this->addError($attribute, $error);
                        }
                    }

                    $transaction->rollBack();

                    return false;

                }


                $this->sendEmail($user);

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

    public function updateApplicant()
    {
        if (!$this->validate()) {
            return null;
        }


        $transaction = Yii::$app->db->beginTransaction();

        try {


            $pass = substr(md5(sha1(microtime())), 0, 8);
            $passHash = Yii::$app->security->generatePasswordHash($pass);


            $user = $this->user ?? new Applicant();
            $user->password_hash = $passHash;
            $user->password = $passHash;
            $user->university_email = $this->university_email;
            $user->status = $this->status;
            $user->updated_at = time();

            if ($user->save()) {

                $userAdditionalData = $this->userAdditionalData ?? new UserAdditionalData();
                $userAdditionalData->first_name = $this->first_name;
                $userAdditionalData->last_name = $this->last_name;
                $userAdditionalData->phone = $this->phone;
                $userAdditionalData->user_id = $user->id;
                $userAdditionalData->faculty = $this->faculty;
                $userAdditionalData->chair = $this->chair;
                $userAdditionalData->dob = $this->dob;

                if ($userAdditionalData->save()) {

                    $userAdmissionData = UserAdmissionData::find()->where(['user_id' => $user->id])->one();
                    if (!$userAdmissionData) {
                        $userAdmissionData = new UserAdmissionData();
                    }

                    $userAdmissionData->education_level = $this->education_level ?? 0;
                    $userAdmissionData->educational_programs = $this->educational_programs ?? 0;
                    $userAdmissionData->user_id = $user->id;

                    if (!$userAdmissionData->save()) {
                        foreach ($userAdmissionData->getErrors() as $attribute => $errors) {
                            foreach ($errors as $error) {
                                $this->addError($attribute, $error);
                            }
                        }

                        $transaction->rollBack();

                        return false;
                    }

                } else {

                    foreach ($userAdditionalData->getErrors() as $attribute => $errors) {
                        foreach ($errors as $error) {
                            $this->addError($attribute, $error);
                        }
                    }

                    $transaction->rollBack();

                    return false;
                }

                if ($this->status == StatusList::STATUS_ACTIVE) {


                    $auth = Yii::$app->authManager;
                    $auth->revokeAll($user->id);

                    $role = $auth->getRole(UserRoles::STUDENT);
                    $auth->assign($role, $user->id);


                    $this->applicantActivationEmail($user, $userAdditionalData, $pass);

                }else if($this->status == StatusList::STATUS_REJECTED){

                    $this->sendRejectEmail($user, $userAdditionalData, $pass);
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
}
