<?php

namespace common\components;

class StatusList
{

    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;
    const STATUS_PENDING = 2;
    const STATUS_REJECTED = 20;


    public static function getStatusLabel($status = null): string
    {
        $statuses = [
            self::STATUS_DELETED => 'Deleted',
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_PENDING => 'Pending',
            self::STATUS_REJECTED => 'Rejected',
        ];

        if ($status === null) {
            return $statuses;
        }

        return $statuses[$status] ?? 'Unknown';
    }


}