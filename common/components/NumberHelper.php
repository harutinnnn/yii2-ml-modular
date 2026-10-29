<?php

namespace common\components;

class NumberHelper
{

    public static function numberZeroFill($number, $zeroFillCount = 1): string
    {

        $str = '';
        if (strlen($number) - 1 < $zeroFillCount) {
            for ($i = 0; $i < $zeroFillCount; $i++) {
                $str .= '0';
            }
        }


        return $str . $number;
    }


}