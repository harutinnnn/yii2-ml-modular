<?php

namespace common\models;

use common\components\StatusList;
use Yii;

/**
 * This is the model class for table "library_item".
 *
 * @property int $id
 * @property int $library_module_id
 * @property int $status
 * @property string|null $url
 * @property string|null $file
 * @property int $created_at
 *
 * @property LibraryModuleMl[] $translations
 *
 * @property LibraryModule $libraryModule
 */
class LibraryItem extends \yii\db\ActiveRecord
{
    /**
     * ENUM field values
     */
    const TYPE_FILE = 'file';
    const TYPE_URL = 'url';


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'library_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url', 'file'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 0],
            [['type'], 'default', 'value' => 'url'],
            [['type'], 'string'],
            ['type', 'in', 'range' => array_keys(self::optsType())],
            [['library_module_id'], 'required'],
            [['library_module_id', 'status', 'created_at'], 'integer'],
            [['url', 'file'], 'string', 'max' => 255],
            [['library_module_id'], 'exist', 'skipOnError' => true, 'targetClass' => LibraryModule::class, 'targetAttribute' => ['library_module_id' => 'id']],
        ];
    }

    /**
     * column type ENUM value labels
     * @return string[]
     */
    public static function optsType()
    {
        return [
            self::TYPE_FILE => 'file',
            self::TYPE_URL => 'url',
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
            'status' => 'Status',
            'url' => 'Url',
            'file' => 'File',
            'created_at' => 'Created At',
            'type' => 'Type',
        ];
    }

    public function getTranslations()
    {
        return $this->hasMany(LibraryItemMl::class, ['library_item_id' => 'id'])->indexBy('lang');
    }

    public static function statusOptions(): array
    {
        return [
            StatusList::STATUS_PENDING => 'Pending',
            StatusList::STATUS_ACTIVE => 'Published'
        ];
    }

    public function beforeSave($insert)
    {

        if (!parent::beforeSave($insert)) {

            return false;
        }

        if ($insert) {
            $this->created_at = time();
        } else {

        }

        return true;
    }

}
