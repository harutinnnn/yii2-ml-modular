<?php

namespace common\components;

class EntityTypes
{
    const APPLICANT = 'APPLICANT';
    const STUDENT = 'STUDENT';
    const TEACHER = 'TEACHER';
    const USER = 'USER';
    const ADMIN = 'ADMIN';
    const JOURNAL = 'JOURNAL';
    const NEWS = 'NEWS';


    public static function getEntityTypes(): array
    {

        return [
            self::APPLICANT => self::APPLICANT,
            self::STUDENT => self::STUDENT,
            self::TEACHER => self::TEACHER,
            self::USER => self::USER,
            self::ADMIN => self::ADMIN,
            self::JOURNAL => self::JOURNAL,
            self::NEWS => self::NEWS,
        ];
    }
}