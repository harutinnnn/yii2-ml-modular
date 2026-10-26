<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "page_block_item_ml".
 *
 * @property int $id
 * @property int $page_block_item_id
 * @property resource $lang
 * @property string $title
 * @property string|null $text
 * @property string|null $url
 *
 * @property PageBlockItem $pageBlockItem
 */
class PageBlockItemMl extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'page_block_item_ml';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text','url'], 'default', 'value' => null],
            [['page_block_item_id', 'lang', 'title'], 'required'],
            [['page_block_item_id'], 'integer'],
            [['text','url'], 'string'],
            [['lang'], 'string', 'max' => 8],
            [['title'], 'string', 'max' => 255],
            [['page_block_item_id', 'lang'], 'unique', 'targetAttribute' => ['page_block_item_id', 'lang']],
            [['page_block_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => PageBlockItem::class, 'targetAttribute' => ['page_block_item_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'page_block_item_id' => 'Page Block Item ID',
            'lang' => 'Lang',
            'title' => 'Title',
            'text' => 'Text',
            'url' => 'Url',
        ];
    }

    /**
     * Gets query for [[PageBlockItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPageBlockItem()
    {
        return $this->hasOne(PageBlockItem::class, ['id' => 'page_block_item_id']);
    }

}
