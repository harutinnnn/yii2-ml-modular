<?php

namespace backend\controllers;

use common\components\UserRoles;
use mdm\admin\controllers\PermissionController as BasePermissionController;
use yii\filters\AccessControl;

class PermissionController extends BasePermissionController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => [UserRoles::SUPER_ADMIN,],
                ],
            ],
        ];

        return $behaviors;
    }
}