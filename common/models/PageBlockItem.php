<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "page_block_item".
 *
 * @property int $id
 * @property int $page_block_id
 * @property int $pos
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property PageBlock $pageBlock
 * @property PageBlockMl[] $translations
 */
class PageBlockItem extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'page_block_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'default', 'value' => null],
            [['pos'], 'default', 'value' => 0],
            [['page_block_id'], 'required'],
            [['page_block_id', 'pos', 'created_at', 'updated_at'], 'integer'],
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
            'pos' => 'Pos',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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

    /**
     * Gets query for [[PageBlockItemMls]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(PageBlockItemMl::class, ['page_block_item_id' => 'id'])->indexBy('lang');
    }

}
