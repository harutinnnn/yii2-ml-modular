<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "library_module_ml".
 *
 * @property int $id
 * @property int $library_module_id
 * @property string $lang
 * @property string $title
 * @property string|null $text
 *
 * @property LibraryModule $libraryModule
 */
class LibraryModuleMl extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'library_module_ml';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text'], 'default', 'value' => null],
            [['library_module_id', 'lang', 'title'], 'required'],
            [['library_module_id'], 'integer'],
            [['lang'], 'string', 'max' => 8],
            [['title', 'text'], 'string', 'max' => 255],
            [['library_module_id', 'lang'], 'unique', 'targetAttribute' => ['library_module_id', 'lang']],
            [['library_module_id'], 'exist', 'skipOnError' => true, 'targetClass' => LibraryModule::class, 'targetAttribute' => ['library_module_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'library_module_id' => 'Library Module ID',
            'lang' => 'Lang',
            'title' => 'Title',
            'text' => 'Text',
        ];
    }

    /**
     * Gets query for [[LibraryModule]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLibraryModule()
    {
        return $this->hasOne(LibraryModule::class, ['id' => 'library_module_id']);
    }

}
