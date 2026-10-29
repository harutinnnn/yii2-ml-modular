<?php

namespace common\components;

use common\models\FrontendLanguage;
use common\models\Language;
use common\models\Setting;
use yii\base\Component;
use yii\helpers\ArrayHelper;

class GlobalData extends Component
{
    public array $labels = [];
    public array $settings = [];
    public $lang = 'am';
    public array $languages = [];
    public $pageData = [];


    public function init()
    {
        parent::init();

        $labels = FrontendLanguage::find()->joinWith('translations t')->where(['type' => FrontendLanguage::TYPE_LABEL])->andWhere(['lang' => $this->lang])->all();
        foreach ($labels as $label) {
            $this->labels[$label['key']] = $label->getTranslation($this->lang)->attributes['text'];
        }

        $this->settings = ArrayHelper::map(Setting::find()->all(), 'key', 'value');


        $this->languages = ArrayHelper::map(Language::find()->all(), 'code', 'name');
    }

    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    public function setPageData($key, $value)
    {

        $this->pageData[$key] = $value;

    }

    public function getLang()
    {
        return $this->lang;
    }
}