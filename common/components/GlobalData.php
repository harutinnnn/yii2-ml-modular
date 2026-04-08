<?php

namespace common\components;

use common\models\FrontendLanguage;
use common\models\Labels;
use common\models\Setting;
use common\models\Settings;
use yii\base\Component;
use yii\helpers\ArrayHelper;

class GlobalData extends Component
{
    public array $labels = [];
    public array $settings = [];
    private string $lang = 'en';

    public function init()
    {
        parent::init();

        $labels = FrontendLanguage::find()->joinWith('translations t')->where(['type' => FrontendLanguage::TYPE_LABEL])->andWhere(['lang' => $this->lang])->all();
        foreach ($labels as $label) {
            $this->labels[$label['key']] = $label->getTranslation($this->lang)->attributes['text'];
        }

        $this->settings = ArrayHelper::map(Setting::find()->all(), 'key', 'value');
    }

    public function setLang(string $lang): void
    {
        $this->lang = $lang;
    }

}