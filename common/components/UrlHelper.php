<?php

namespace common\components;

class UrlHelper
{

    public static function findAndChangeUrlLang($url, $langCode): string
    {
        $url = explode('/', trim($url, '/'));
        if (isset($url[0])) {
            $url[0] = $langCode;
        }
        return '/' . implode('/', $url);
    }

}