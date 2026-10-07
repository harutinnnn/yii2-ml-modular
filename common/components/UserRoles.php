<?php

namespace common\components;

class UserRoles
{
    const SUPER_ADMIN = 'super_admin';
    const ADMIN = 'admin';
    const ADMINISTRATIVE_STAFF = 'administrative_staff';
    const APPLICANT = 'applicant';
    const STUDENT = 'student';
    const TEACHER = 'teacher';


    public static function getLoginUserTypeOptions(): array
    {

        return [
            self::STUDENT => I18n::translate(self::STUDENT),
            self::TEACHER => I18n::translate(self::TEACHER),
            self::ADMINISTRATIVE_STAFF => I18n::translate(self::ADMINISTRATIVE_STAFF),
        ];

    }

}