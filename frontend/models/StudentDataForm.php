<?php

namespace frontend\models;

use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class StudentDataForm extends Model
{
    public $id;
    public $first_name;
    public $last_name;
    public $dob;
    public $passport_details;
    public $phone;
    public $email;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name','dob', 'passport_details','phone','email'], 'required'],
//            [['passport_details', 'phone', 'email'], 'required'],
            [['first_name', 'last_name', 'phone', 'passport_details', 'email'], 'string'],
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
        ];
    }
}
