<?php

namespace backend\controllers;

use common\components\UserRoles;
use mdm\admin\controllers\RoleController as BaseRoleController;
use yii\filters\AccessControl;

class RoleController extends BaseRoleController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => [UserRoles::SUPER_ADMIN],
                ],
            ],
        ];

        return $behaviors;
    }
}