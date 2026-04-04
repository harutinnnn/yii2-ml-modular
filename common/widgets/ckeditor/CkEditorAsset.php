<?php

namespace common\widgets\ckeditor;

use yii\web\AssetBundle;

class CkEditorAsset extends AssetBundle
{
    public $sourcePath = '@vendor/skeeks/yii2-ckeditor/src/assets';
    public $js = [
        'ckeditor/ckeditor.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
