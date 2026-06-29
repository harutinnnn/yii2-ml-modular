<?php

namespace common\behaviors;

use Yii;
use \mohorev\file\UploadBehavior;
use \yii\helpers\Url;

/**
 * This is the behavior for upload file
 *
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $avatar
 */
class TFileUploadBehavior extends UploadBehavior
{
    /**
     * Generates random filename.
     * Overwrite parent logic
     * @param $file
     * @return string
     */
    protected function generateFileName($file)
    {
        return time() . '-' .  uniqid() . '.' . $file->extension;
    }

    /**
     * Returns file url for the attribute.
     * @param string $attribute
     * @return string|null  
     */
    public function getUploadUrl($attribute)
    {
        $model = $this->owner;
        $url = $this->resolvePath($this->url);
        $fileName = $model->getOldAttribute($attribute);
        $file =  $url . '/' . $fileName;
        return $fileName ? Url::to($file, '') : null;
    }

    public function getUploadPath($attribute, $old = false)
    {
        $model = $this->owner;
        $path = $this->resolvePath($this->path);
        $fileName = ($old === true) ? $model->getOldAttribute($attribute) : $model->$attribute;
        return $fileName ? Yii::getAlias($path . '/' . $fileName) : null;
    }
}
