<?php

namespace common\components;

class FileHelper
{


    public static function getFileTypeIcon($type = '', $file)
    {

        switch ($type) {
            case 'jpg':
            case 'jpeg':
            case 'png':
                return $file;
            case 'pdf':
                return '/images/file-types/pdf.png';
            case 'doc':
            case 'docx':
                return '/images/file-types/docx.png';
            case 'xls':
            case 'xlsx':
                return '/images/file-types/xlsx.png';
        }


    }

}