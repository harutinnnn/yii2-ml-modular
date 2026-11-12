<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "library_item_ml".
 *
 * @property int $id
 * @property int $library_item_id
 * @property string $lang
 * @property string $title
 * @property string|null $text
 *
 * @property LibraryItem $libraryItem
 */
class LibraryItemMl extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'library_item_ml';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text'], 'default', 'value' => null],
            [['library_item_id', 'lang', 'title'], 'required'],
            [['id', 'library_item_id'], 'integer'],
            [['lang'], 'string', 'max' => 8],
            [['title', 'text'], 'string'],
            [['library_item_id', 'lang'], 'unique', 'targetAttribute' => ['library_item_id', 'lang']],
            [['library_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => LibraryItem::class, 'targetAttribute' => ['library_item_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'library_item_id' => 'Library Item ID',
            'lang' => 'Lang',
            'title' => 'Title',
            'text' => 'Text',
        ];
    }

    /**
     * Gets query for [[LibraryItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLibraryItem()
    {
        return $this->hasOne(LibraryItem::class, ['id' => 'library_item_id']);
    }

}
