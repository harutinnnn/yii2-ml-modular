<?php

namespace common\components;

use common\models\User;
use Transliterator;

class EmailHelper
{
    const EMAIL_SUFFIX = '@asue.am';

    public static function getUniqueEmail(): string
    {
        return bin2hex(random_bytes(4)) . self::EMAIL_SUFFIX;
    }


    public static function generateUniqueEmail(string $firstName, string $lastName): string
    {
        $firstName = strtolower(trim($firstName));
        $lastName = strtolower(trim($lastName));

        // Optional: remove spaces
        $firstName = preg_replace('/\s+/', '', $firstName);
        $lastName = preg_replace('/\s+/', '', $lastName);

        $base = $firstName . '.' . $lastName;

        $email = $base . self::EMAIL_SUFFIX;

        // First try without random number
        if (!User::find()->where(['email' => $email])->exists()) {
            return $email;
        }

        // If exists, keep generating until unique
        do {
            $random = random_int(100, 9999);

            $email = $base . '.' . $random . self::EMAIL_SUFFIX;

        } while (User::find()->where(['email' => $email])->exists());

        return $email;
    }

}