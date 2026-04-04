<?php

namespace common\models;

use yii\db\ActiveRecord;

class MenuMl extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%menu_ml}}';
    }

    public function rules(): array
    {
        return [
            [['menu_id', 'lang', 'title'], 'required'],
            [['menu_id'], 'integer'],
            [['meta_desc', 'description', 'meta_keywords'], 'string'],
            [['lang'], 'string', 'max' => 8],
            [['title', 'meta_title'], 'string', 'max' => 255],
            [['menu_id', 'lang'], 'unique', 'targetAttribute' => ['menu_id', 'lang']],
            [['menu_id'], 'exist', 'targetClass' => Menu::class, 'targetAttribute' => ['menu_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'menu_id' => 'Menu',
            'lang' => 'Language',
            'title' => 'Title',
            'meta_title' => 'Meta Title',
            'meta_desc' => 'Meta Description',
            'description' => 'Description',
            'meta_keywords' => 'Meta Keywords',
        ];
    }

    public function getMenu()
    {
        return $this->hasOne(Menu::class, ['id' => 'menu_id']);
    }
}
