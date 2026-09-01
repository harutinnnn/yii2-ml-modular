<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class AdmissionForm extends Model
{
    public $name;
    public $surname;
    public $email;
    public $phone;
    public $education_level;
    public $educational_programs;
    public $consent_processing_personal_data;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'surname', 'email', 'phone', 'education_level', 'educational_programs'], 'required'],
            ['consent_processing_personal_data', 'required', 'requiredValue' => 1, 'message' => 'The field is required.'],
            [['education_level', 'educational_programs'], 'integer', 'min' => 1],
            ['email', 'email'],
            ['email', 'unique',
                'targetClass' => \common\models\Admissions::class,
                'targetAttribute' => 'email',
                'message' => 'This email is already registered.',
            ],
        ];
    }

    public function sendEmail($email)
    {
        return Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
            ->setReplyTo([$this->email => $this->name])
            ->setSubject($this->subject)
            ->setTextBody($this->body)
            ->send();
    }
}
