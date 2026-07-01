<?php

namespace backend\controllers;

use common\components\UserRoles;
use mdm\admin\controllers\AssignmentController as BaseAssignmentController;
use yii\filters\AccessControl;

class AssignmentController extends BaseAssignmentController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => [UserRoles::SUPER_ADMIN,], // only admin role
                ],
            ],
        ];

        return $behaviors;
    }
}