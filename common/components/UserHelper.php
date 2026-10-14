<?php

namespace common\components;

use common\models\User;

class UserHelper
{

    public static function getUsersByRoles($roles = [])
    {
        $query = User::find();

        $query->innerJoin('auth_assignment aa', 'aa.user_id = user.id');

        foreach ($roles as $role) {
            $query->orWhere(['aa.item_name' => $role]);
        }

        return $query->all();
    }

    public static function getUserName(User $user): string
    {

        $first_name = ($user->additional->first_name ?? '');
        $last_name = ($user->additional->last_name ?? '');
        $full_name = ($user->full_name ?? '');
        $email = $user->email;

        if (strlen(trim($first_name)) || strlen(trim($last_name))) {
            $name = $first_name . ' ' . $last_name;
        } else if (strlen(trim($full_name))) {
            $name = $full_name;
        } else {
            $name = $email;
        }

        return $name;

    }

}