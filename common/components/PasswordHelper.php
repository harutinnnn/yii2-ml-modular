<?php

namespace common\components;

class PasswordHelper
{
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
}