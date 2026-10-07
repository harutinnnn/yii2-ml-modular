<?php

namespace frontend\models;

use common\components\I18n;
use common\models\ApplicationsAndInquiries;
use yii\base\Model;

class ApplicationsAndInquiriesForm extends Model
{

    public $id;
    public $user_id;
    public $type;
    public $subject;
    public $description;
    public $file_or_url;


    public function rules()
    {
        return [
            [['subject', 'type', 'description'], 'required'],
            [['subject', 'type', 'description'], 'string']
        ];
    }


    public static function getTypes()
    {
        return [
            ApplicationsAndInquiries::TYPE_ARTICLE => I18n::translate(ApplicationsAndInquiries::TYPE_ARTICLE),
            ApplicationsAndInquiries::TYPE_REPORT => I18n::translate(ApplicationsAndInquiries::TYPE_REPORT),
            ApplicationsAndInquiries::TYPE_RESEARCH => I18n::translate(ApplicationsAndInquiries::TYPE_RESEARCH),
        ];
    }

}