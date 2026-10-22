<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "page_side_menu_items_per_menu".
 *
 * @property int $menu_id
 * @property int $side_menu_id
 *
 * @property Menu $menu
 * @property Menu $sideMenu
 */
class PageSideMenuItemsPerMenu extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'page_side_menu_items_per_menu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['menu_id', 'side_menu_id'], 'required'],
            [['menu_id', 'side_menu_id'], 'integer'],
            [['menu_id', 'side_menu_id'], 'unique', 'targetAttribute' => ['menu_id', 'side_menu_id']]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'menu_id' => 'Menu ID',
            'side_menu_id' => 'Side Menu ID',
        ];
    }

    /**
     * Gets query for [[Menu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(Menu::class, ['id' => 'menu_id']);
    }

    /**
     * Gets query for [[SideMenu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSideMenu()
    {
        return $this->hasOne(Menu::class, ['id' => 'side_menu_id']);
    }

}
