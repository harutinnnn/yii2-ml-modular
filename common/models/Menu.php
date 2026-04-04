<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Menu extends ActiveRecord
{
    public const STATUS_PENDING = 0;
    public const STATUS_PUBLISHED = 1;

    public static function tableName(): string
    {
        return '{{%menu}}';
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules(): array
    {
        return [
            [['status', 'show_in_menu', 'url', 'section_id'], 'required'],
            [['status', 'show_in_menu', 'position', 'content_id', 'section_id', 'parent_id'], 'integer'],
            [['url', 'image', 'header_image'], 'string', 'max' => 255],
            [['status'], 'in', 'range' => array_keys(self::statusOptions())],
            [['show_in_menu'], 'in', 'range' => [0, 1]],
            [['url'], 'unique'],
            [['content_id'], 'exist', 'targetClass' => Content::class, 'targetAttribute' => ['content_id' => 'id'], 'skipOnEmpty' => true],
            [['section_id'], 'exist', 'targetClass' => Section::class, 'targetAttribute' => ['section_id' => 'id'], 'skipOnEmpty' => true],
            [['parent_id'], 'exist', 'targetClass' => self::class, 'targetAttribute' => ['parent_id' => 'id'], 'skipOnEmpty' => true],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'show_in_menu' => 'Show In Menu',
            'position' => 'Position',
            'content_id' => 'Content',
            'section_id' => 'Section',
            'parent_id' => 'Parent',
            'url' => 'URL',
            'image' => 'Image',
            'header_image' => 'Header Image',
        ];
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PUBLISHED => 'Published',
        ];
    }

    public static function booleanOptions(): array
    {
        return [
            0 => 'No',
            1 => 'Yes',
        ];
    }

    public function getContent()
    {
        return $this->hasOne(Content::class, ['id' => 'content_id']);
    }

    public function getSection()
    {
        return $this->hasOne(Section::class, ['id' => 'section_id']);
    }

    public function getParent()
    {
        return $this->hasOne(self::class, ['id' => 'parent_id']);
    }

    public function getChildren()
    {
        return $this->hasMany(self::class, ['parent_id' => 'id'])->orderBy(['position' => SORT_ASC, 'id' => SORT_ASC]);
    }

    public function getTranslations()
    {
        return $this->hasMany(MenuMl::class, ['menu_id' => 'id'])->indexBy('lang');
    }

    public function getTranslation(string $lang): ?MenuMl
    {
        $translations = $this->translations;
        return $translations[$lang] ?? null;
    }

    public function getDisplayTitle(): string
    {
        $defaultLanguage = Language::find()->where(['is_default' => 1])->select('code')->scalar();
        if ($defaultLanguage) {
            $translation = $this->getTranslation($defaultLanguage);
            if ($translation !== null && $translation->title !== '') {
                return $translation->title;
            }
        }

        foreach ($this->translations as $translation) {
            if ($translation->title !== '') {
                return $translation->title;
            }
        }

        return 'Untitled';
    }

    public function getStatusLabel(): string
    {
        return self::statusOptions()[$this->status] ?? 'Unknown';
    }
}
