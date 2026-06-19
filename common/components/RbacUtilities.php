<?php

namespace common\components;

class RbacUtilities
{

    public static function allowRoles($roles = []): bool
    {

        foreach ($roles as $role) {
            if (\Yii::$app->user->can($role))
                return true;
        }
        return false;
    }


}