<?php

namespace common\models;

use common\components\StatusList;
use Yii;

/**
 * This is the model class for table "page_block".
 *
 * @property int $id
 * @property int $status
 * @property string|null $type
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property PageBlockItem[] $pageBlockItems
 * @property PageBlockMl[] $translations
 */
class PageBlock extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const TYPE_LIST = 'list';
    const TYPE_CART = 'cart';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'page_block';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 0],
            [['type'], 'default', 'value' => 'cart'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['type'], 'string'],
            ['type', 'in', 'range' => array_keys(self::typeOptions())],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'type' => 'Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[PageBlockItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPageBlockItems()
    {
        return $this->hasMany(PageBlockItem::class, ['page_block_id' => 'id']);
    }


    public function getTranslations()
    {
        return $this->hasMany(PageBlockMl::class, ['page_block_id' => 'id'])->indexBy('lang');
    }

    public static function statusOptions(): array
    {
        return [
            StatusList::STATUS_PENDING => 'Pending',
            StatusList::STATUS_ACTIVE => 'Published',
        ];
    }

    public static function typeOptions(): array
    {
        return [
            self::TYPE_CART => 'Cart',
            self::TYPE_LIST => 'List',
        ];
    }

    public static function getType($type): string{
        return  self::typeOptions()[$type];
    }
}
