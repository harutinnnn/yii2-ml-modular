<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        $adminPanel = $auth->createPermission('adminPanel');
        $auth->add($adminPanel);

        $admin = $auth->createRole('admin');
        $auth->add($admin);

        $auth->addChild($admin, $adminPanel);

        // user ID 1 = admin
        $auth->assign($admin, 1);

        echo "Done\n";
    }
}