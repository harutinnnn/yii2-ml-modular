<?php

namespace backend\modules\user\models;

use common\components\UserRoles;
use common\models\Applicant;
use common\models\Chairs;
use common\models\Faculties;
use common\models\UserAdditionalData;
use common\models\UserFacultyChairLcp;
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

    public ?Applicant $user = null;
    public ?UserAdditionalData $userAdditionalData = null;
    public ?UserFacultyChairLcp $userFacultyChairLcp = null;

    public $id;
    public $status;
    public $email;
    public $phone;
    public $faculty_id;
    public $chair_id;
    public $first_name;
    public $last_name;
    public $created_at;
    public $updated_at;
    public $verification_token;

    public $faculty_title;
    public $chair_title;

    public const STATUS_INACTIVE = 9;
    public const STATUS_ACTIVE = 10;


    public function __construct(?Applicant $user = null, $config = [])
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

            if ($user->faculty) {
                $this->faculty_id = (int)$user->faculty->faculty_id ?? 0;
                $this->chair_id = (int)$user->faculty->chair_id ?? 0;

                $faculty = Faculties::find()->where(['id' => $this->faculty_id])->one();
                $this->faculty_title = $faculty->getDisplayTitle();

                $chair = Chairs::find()->where(['id' => $this->chair_id])->one();
                $this->chair_title = $chair->getDisplayTitle();
            } else {
                $this->faculty_id = 0;
                $this->chair_id = 0;
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
            'phone',
            'status',
            'first_name',
            'last_name',
            'faculty_id',
            'chair_id',
        ];

        $scenarios[self::SCENARIO_UPDATE] = [
            'status',
            'phone',
            'first_name',
            'last_name',
            'faculty_id',
            'chair_id',
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
            [['first_name', 'last_name', 'faculty_id', 'chair_id', 'phone'], 'required'],
            [['first_name', 'last_name', 'phone'], 'string'],
            [['status', 'faculty_id', 'chair_id', 'created_at', 'updated_at'], 'integer'],

            [['email'], 'string', 'max' => 255, 'on' => self::SCENARIO_CREATE],
            [['email'], 'required', 'on' => self::SCENARIO_CREATE],
            ['email', 'email', 'on' => self::SCENARIO_CREATE],

            [
                'email',
                'unique',
                'targetClass' => Applicant::class,
                'targetAttribute' => 'email',
                'message' => 'This email has already been taken.',
                'on' => self::SCENARIO_CREATE
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
            'faculty_id' => 'Faculty',
            'chair_id' => 'Chair',
            'email' => 'Email',
            'phone' => 'Phone',
            'status' => 'Status',
            'first_name' => 'First name',
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
                ['html' => 'rejectApplicant-html', 'text' => 'rejectApplicant-text'],
                ['user' => $user, 'userAdditionalData' => $userAdditionalData]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }

    public function sendApproveEmail($user, $userAdditionalData, $pass): bool
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'approveApplicant-html', 'text' => 'approveApplicant-text'],
                ['user' => $user, 'userAdditionalData' => $userAdditionalData, 'pass' => $pass]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }


    public function registerApplicant()
    {

        if (!$this->validate()) {
            return null;
        }


        $transaction = Yii::$app->db->beginTransaction();

        try {

//            dd($this);
            $pass = substr(md5(sha1(microtime())), 0, 8);

            $user = new Applicant();
            $user->email = $this->email;
            $user->password = $pass;
            $user->status = $this->status;
            $user->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
            $user->created_at = time();
            $user->updated_at = time();

            if ($user->save()) {

                $auth = Yii::$app->authManager;

                $role = $auth->getRole(UserRoles::APPLICANT);
                $auth->assign($role, $user->id);

                $userAdditionalData = new UserAdditionalData();
                $userAdditionalData->first_name = $this->first_name;
                $userAdditionalData->last_name = $this->last_name;
                $userAdditionalData->phone = $this->phone;
                $userAdditionalData->user_id = $user->id;

                if ($userAdditionalData->save()) {

                    $userFacultyChairLcp = new UserFacultyChairLcp();
                    $userFacultyChairLcp->chair_id = $this->chair_id ?? 0;
                    $userFacultyChairLcp->user_id = $user->id;
                    $userFacultyChairLcp->faculty_id = $this->faculty_id ?? 0;
                    $userFacultyChairLcp->save();
                }

                $this->sendEmail($user);
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


            $user = $this->user ?? new Applicant();
            $user->password = $pass;
            $user->status = $this->status;
            $user->updated_at = time();

            if ($user->save()) {


                $userAdditionalData = $this->userAdditionalData ?? new UserAdditionalData();
                $userAdditionalData->first_name = $this->first_name;
                $userAdditionalData->last_name = $this->last_name;
                $userAdditionalData->phone = $this->phone;
                $userAdditionalData->user_id = $user->id;

                if ($userAdditionalData->save()) {


                    $userFacultyChairLcp = $this->userFacultyChairLcp ?? new UserFacultyChairLcp();
                    $userFacultyChairLcp->chair_id = $this->chair_id ?? 0;
                    $userFacultyChairLcp->user_id = $user->id;
                    $userFacultyChairLcp->faculty_id = $this->faculty_id ?? 0;
                    $userFacultyChairLcp->save();
                }

                if ($this->status == Applicant::STATUS_ACTIVE) {

                    $auth = Yii::$app->authManager;
                    $auth->revokeAll($user->id);

                    $role = $auth->getRole(UserRoles::STUDENT);
                    $auth->assign($role, $user->id);

                    $this->sendApproveEmail($user, $userAdditionalData, $pass);

                } else if ($this->status == Applicant::STATUS_REJECTED) {

                    $this->sendRejectEmail($user, $userAdditionalData);

                }
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
