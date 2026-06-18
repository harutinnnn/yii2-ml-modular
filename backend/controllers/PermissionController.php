<?php

namespace backend\controllers;

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
                    'roles' => ['admin'],
                ],
            ],
        ];

        return $behaviors;
    }
}