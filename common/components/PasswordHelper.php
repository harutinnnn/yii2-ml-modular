<?php

namespace common\components;

use backend\modules\user\models\User;

class PasswordHelper
{
    /**
     * @param int $length
     * @param int $level
     * @return string
     * @throws \Random\RandomException
     */
    public static function generate(int $length = 12, int $level = 3): string
    {
        $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $symbols = '!@#$%^&*()-_=+[]{}<>?';

        $chars = $letters;

        if ($level >= 2) {
            $chars .= $numbers;
        }

        if ($level >= 3) {
            $chars .= $symbols;
        }

        $password = '';
        $maxIndex = strlen($chars) - 1;

        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, $maxIndex)];
        }

        return $password;
    }

    public static function generateStudentNumber($year): int
    {

        $query = User::find()
            ->innerJoin('auth_assignment aa', 'aa.user_id = user.id')
            ->where(['add.year' => $year])
            ->andWhere([
                'or',
                ['aa.item_name' => UserRoles::STUDENT],
                ['aa.item_name' => UserRoles::APPLICANT]
            ])
            ->joinWith('additional add')->orderBy(['add.studnet_number' => SORT_DESC])->one();

        return intval($query->additional->studnet_number ?? 0) + 1;
    }

    public static function generateStudentId($studentNumber, $year): string
    {
        return 'ASUE-' . $year . '-' . $studentNumber;
    }

}