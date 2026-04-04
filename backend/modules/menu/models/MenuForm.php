<?php

namespace backend\modules\menu\models;

use common\models\Content;
use common\models\Language;
use common\models\Menu;
use common\models\MenuMl;
use common\models\Section;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class MenuForm extends Model
{
    public ?Menu $menu = null;
    public int $status = Menu::STATUS_PENDING;
    public int $show_in_menu = 1;
    public ?int $position = null;
    public ?int $content_id = null;
    public ?int $section_id = null;
    public ?int $parent_id = null;
    public string $url = '';
    public ?string $image = null;
    public ?string $header_image = null;
    public array $translations = [];
    public $imageFile = null;
    public $headerImageFile = null;

    private ?array $_languages = null;

    public function __construct(?Menu $menu = null, $config = [])
    {
        $this->menu = $menu;
        parent::__construct($config);

        if ($this->menu !== null) {
            $this->status = (int) $this->menu->status;
            $this->show_in_menu = (int) $this->menu->show_in_menu;
            $this->position = $this->menu->position;
            $this->content_id = $this->menu->content_id;
            $this->section_id = $this->menu->section_id;
            $this->parent_id = $this->menu->parent_id;
            $this->url = (string) $this->menu->url;
            $this->image = $this->menu->image;
            $this->header_image = $this->menu->header_image;
            foreach ($this->menu->translations as $translation) {
                $this->translations[$translation->lang] = [
                    'title' => $translation->title,
                    'meta_title' => $translation->meta_title,
                    'meta_desc' => $translation->meta_desc,
                    'description' => $translation->description,
                    'meta_keywords' => $translation->meta_keywords,
                ];
            }
        }

        foreach ($this->getLanguages() as $language) {
            $this->translations[$language->code] = array_merge([
                'title' => '',
                'meta_title' => '',
                'meta_desc' => '',
                'description' => '',
                'meta_keywords' => '',
            ], $this->translations[$language->code] ?? []);
        }
    }

    public function rules(): array
    {
        return [
            [['status', 'show_in_menu', 'url', 'section_id'], 'required'],
            [['status', 'show_in_menu', 'position', 'content_id', 'section_id', 'parent_id'], 'integer'],
            [['status'], 'in', 'range' => array_keys(Menu::statusOptions())],
            [['show_in_menu'], 'in', 'range' => [0, 1]],
            [['url'], 'string', 'max' => 255],
            [['translations'], 'safe'],
            [['translations'], 'validateTranslations'],
            [['imageFile', 'headerImageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => ['png', 'jpg', 'jpeg', 'gif', 'webp']],
        ];
    }

    public function load($data, $formName = null): bool
    {
        $scope = $formName ?? $this->formName();
        if ($scope !== '' && isset($data[$scope]) && is_array($data[$scope])) {
            foreach (['content_id', 'section_id', 'parent_id', 'position'] as $field) {
                if (array_key_exists($field, $data[$scope]) && $data[$scope][$field] === '') {
                    $data[$scope][$field] = null;
                }
            }
        }

        return parent::load($data, $formName);
    }

    public function attributeLabels(): array
    {
        return [
            'status' => 'Status',
            'show_in_menu' => 'Show In Menu',
            'position' => 'Position',
            'content_id' => 'Content',
            'section_id' => 'Section',
            'parent_id' => 'Parent',
            'url' => 'URL',
            'imageFile' => 'Image',
            'headerImageFile' => 'Header Image',
        ];
    }

    public function validateTranslations(string $attribute): void
    {
        foreach ($this->getLanguages() as $language) {
            $data = $this->translations[$language->code] ?? [];
            if (trim((string) ($data['title'] ?? '')) === '') {
                $this->addError("translations[{$language->code}][title]", "Title is required for {$language->name}.");
            }
        }
    }

    public function save(): bool
    {
        $this->imageFile = UploadedFile::getInstance($this, 'imageFile');
        $this->headerImageFile = UploadedFile::getInstance($this, 'headerImageFile');

        if (!$this->validate()) {
            return false;
        }

        $menu = $this->menu ?? new Menu();
        $menu->status = $this->status;
        $menu->show_in_menu = $this->show_in_menu;
        $menu->position = $this->position;
        $menu->content_id = $this->content_id ?: null;
        $menu->section_id = $this->section_id ?: null;
        $menu->parent_id = $this->parent_id ?: null;
        $menu->url = trim($this->url);

        if ($this->imageFile !== null) {
            $menu->image = $this->saveUpload($this->imageFile);
        }
        if ($this->headerImageFile !== null) {
            $menu->header_image = $this->saveUpload($this->headerImageFile);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$menu->save()) {
                $this->addErrors($menu->getErrors());
                $transaction->rollBack();
                return false;
            }

            MenuMl::deleteAll(['menu_id' => $menu->id]);

            foreach ($this->getLanguages() as $language) {
                $data = $this->translations[$language->code];
                $translation = new MenuMl();
                $translation->menu_id = $menu->id;
                $translation->lang = $language->code;
                $translation->title = trim((string) $data['title']);
                $translation->meta_title = trim((string) ($data['meta_title'] ?? '')) ?: null;
                $translation->meta_desc = trim((string) ($data['meta_desc'] ?? '')) ?: null;
                $translation->description = trim((string) ($data['description'] ?? '')) ?: null;
                $translation->meta_keywords = trim((string) ($data['meta_keywords'] ?? '')) ?: null;

                if (!$translation->save()) {
                    $this->addErrors($translation->getErrors());
                    $transaction->rollBack();
                    return false;
                }
            }

            $transaction->commit();
            $this->menu = $menu;
            return true;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function getLanguages(): array
    {
        if ($this->_languages === null) {
            $this->_languages = Language::find()->where(['is_active' => 1])->orderBy(['sort_order' => SORT_ASC, 'name' => SORT_ASC])->all();
        }
        return $this->_languages;
    }

    public static function contentOptions(): array
    {
        $items = Content::find()->with('translations')->orderBy(['id' => SORT_DESC])->all();
        $result = ['' => 'None'];
        foreach ($items as $item) {
            $result[$item->id] = '#' . $item->id . ' ' . $item->getDisplayTitle();
        }
        return $result;
    }

    public static function sectionOptions(): array
    {
        $items = Section::find()->orderBy(['position' => SORT_ASC, 'title' => SORT_ASC, 'id' => SORT_ASC])->all();
        $result = [];
        foreach ($items as $item) {
            $result[$item->id] = $item->title;
        }
        return $result;
    }

    public static function parentOptions(?int $excludeId = null): array
    {
        $items = Menu::find()->with('translations')->orderBy(['position' => SORT_ASC, 'id' => SORT_ASC])->all();
        $result = ['' => 'No Parent'];
        foreach ($items as $item) {
            if ($excludeId !== null && $item->id === $excludeId) {
                continue;
            }
            $result[$item->id] = '#' . $item->id . ' ' . $item->getDisplayTitle();
        }
        return $result;
    }

    protected function saveUpload(UploadedFile $file): string
    {
        $basePath = dirname(__DIR__, 4) . '/frontend/web/uploads/menu';
        FileHelper::createDirectory($basePath);
        $name = Yii::$app->security->generateRandomString(16) . '.' . $file->extension;
        $file->saveAs($basePath . '/' . $name);
        return '/uploads/menu/' . $name;
    }
}
