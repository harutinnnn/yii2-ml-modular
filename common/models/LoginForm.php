<?php

namespace common\models;

use common\components\I18n;
use common\components\UserRoles;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $usertype;
    public $email;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // email and password are both required
            [['email', 'password', 'usertype'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['usertype', 'validateUserRole'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect email or password.');
            }
        }
    }

    public function validateUserRole($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user) {
                $this->addError($attribute, 'Incorrect email or password.');
            } else if ($user && $user->roles) {
                $isRole = false;
                foreach ($user->roles as $role) {
                    if ($role->item_name == $this->usertype) {
                        $isRole = true;
                    }
                }
                if (!$isRole) {
                    $this->addError($attribute, 'You dont have permission of role ' . I18n::translate($this->usertype));
                }
            }
        }
    }

    /**
     * Logs in a user using the provided email and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }

    /**
     * Finds user by [[email]]
     *
     * @return User|null
     */
    protected function getUser($userType = null)
    {
        if ($this->_user === null) {
            $this->_user = User::findByEmailRole($this->email, $this->usertype);
        }

        return $this->_user;
    }


    public static function userRoleAndRedirect($id, $controller, $usertype = null)
    {


        if (!$usertype) {

            $auth = Yii::$app->authManager;
            $roles = $auth->getRolesByUser(Yii::$app->user->id);

            if (!empty($roles)) {
                $roleKeys = array_keys($roles);
                $usertype = reset($roleKeys);
            }else{
                Yii::$app->user->logout();
                return $controller->redirect('/' . Yii::$app->globalData->lang . '/login');
            }
        }


        Yii::$app->session->set('userType', $usertype);

        if ($usertype == UserRoles::STUDENT) {

            return $controller->redirect('/' . Yii::$app->globalData->lang . '/student/student-personal-dashboard');

        } else if ($usertype == UserRoles::TEACHER) {

            return $controller->redirect('/' . Yii::$app->globalData->lang . '/lecturer/personal-dashboard');

        } else if ($usertype == UserRoles::ADMINISTRATIVE_STAFF) {

            return $controller->redirect('/' . Yii::$app->globalData->lang . '/administrative/personal-dashboard');

        } else {

            return $controller->goHome();
        }
    }

}
