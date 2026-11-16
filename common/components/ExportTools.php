<?php

namespace common\components;

use common\helpers\EditorJsHelper;
use kartik\export\ExportMenu;

class ExportTools
{

    public static function export($dataProvider)
    {


        $columns = [
            'id',
        ];
        foreach (['am', 'en', 'ru'] as $language) {
            foreach (['title', 'text'] as $field) {
                $columns[] = [
                    'label' => strtoupper($language) . ' ' . ucfirst($field),

                    'value' => function ($model) use ($language, $field) {
                        foreach ($model->translations as $translation) {
                            if ($translation->lang === $language) {
                                if ($field == 'text') {
                                    return EditorJsHelper::render($translation->$field);
                                } else {
                                    return $translation->$field;
                                }
                            }
                        }
                        return '';
                    },
                ];
            }
        }
        $columns[] = 'created_at';
        $columns[] = 'updated_at';

        return ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => $columns,
            'filename' => 'users',
            'clearBuffers' => true,
            'bsVersion' => '4.x',
            'exportConfig' => [
                ExportMenu::FORMAT_HTML => false,
                ExportMenu::FORMAT_CSV => false,
                ExportMenu::FORMAT_TEXT => false,
                ExportMenu::FORMAT_PDF => false,
                ExportMenu::FORMAT_EXCEL => false,

                ExportMenu::FORMAT_EXCEL_X => [
                    'label' => 'Excel XLSX',
                ],
            ],
        ]);

    }


}