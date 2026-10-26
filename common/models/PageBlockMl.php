<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "page_block_ml".
 *
 * @property int $id
 * @property int $page_block_id
 * @property string $lang
 * @property string $title
 * @property string|null $pre_title
 *
 * @property PageBlock $pageBlock
 */
class PageBlockMl extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'page_block_ml';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pre_title'], 'default', 'value' => null],
            [['page_block_id', 'lang', 'title'], 'required'],
            [['page_block_id'], 'integer'],
            [['lang'], 'string', 'max' => 8],
            [['title', 'pre_title'], 'string', 'max' => 255],
            [['page_block_id', 'lang'], 'unique', 'targetAttribute' => ['page_block_id', 'lang']],
            [['page_block_id'], 'exist', 'skipOnError' => true, 'targetClass' => PageBlock::class, 'targetAttribute' => ['page_block_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'page_block_id' => 'Page Block ID',
            'lang' => 'Lang',
            'title' => 'Title',
            'pre_title' => 'Pre Title',
        ];
    }

    /**
     * Gets query for [[PageBlock]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPageBlock()
    {
        return $this->hasOne(PageBlock::class, ['id' => 'page_block_id']);
    }

}
