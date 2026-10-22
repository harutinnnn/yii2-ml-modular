<?php

namespace common\components;

class DateHelper
{


    const MONTHS_SHORT = [
        'en' => [
            1 => 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
        ],
        'am' => [
            1 => 'Հնվ', 'Փտր', 'Մրտ', 'Ապր', 'Մյս', 'Հնս',
            'Հլս', 'Օգս', 'Սեպ', 'Հոկ', 'Նոյ', 'Դեկ'
        ],
        'ru' => [
            1 => 'янв', 'фев', 'мар', 'апр', 'май', 'июн',
            'июл', 'авг', 'сен', 'окт', 'ноя', 'дек'
        ],
    ];

    const MONTHS = [
        'en' => [
            1 => 'January', 'February', 'March', 'April',
            'May', 'June', 'July', 'August',
            'September', 'October', 'November', 'December'
        ],
        'am' => [
            1 => 'Հունվար', 'Փետրվար', 'Մարտ', 'Ապրիլ',
            'Մայիս', 'Հունիս', 'Հուլիս', 'Օգոստոս',
            'Սեպտեմբեր', 'Հոկտեմբեր', 'Նոյեմբեր', 'Դեկտեմբեր'
        ],
        'ru' => [
            1 => 'Январь', 'Февраль', 'Март', 'Апрель',
            'Май', 'Июнь', 'Июль', 'Август',
            'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'
        ],
    ];


    public static function getMonthsShort($date, $lang = 'en'): string
    {
        $dateDat = date('m',strtotime($date));
        return self::MONTHS_SHORT[$lang][intval($dateDat)] ?? '';
    }

    public static function getMonth($date, $lang = 'en'): string
    {
        $dateDat = date('m',strtotime($date));
        return self::MONTHS[$lang][intval($dateDat)] ?? '';
    }



}