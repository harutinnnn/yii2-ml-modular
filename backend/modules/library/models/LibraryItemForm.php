<?php

namespace backend\modules\library\models;

use common\components\StatusList;
use common\models\Language;
use common\models\LibraryItem;
use common\models\LibraryItemMl;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class LibraryItemForm extends Model
{
    public ?LibraryItem $library_item = null;
    public int $status = StatusList::STATUS_ACTIVE;
    public string $type = LibraryItem::TYPE_URL;
    public int $id = 0;
    public int $library_module_id = 0;
    public string $url = '';
    public string $file = '';
    public array $translations = [];

    public $libraryItemFile = null;

    private ?array $_languages = null;

    public function __construct(?LibraryItem $library_item = null, $config = [])
    {
        $this->library_item = $library_item;
        parent::__construct($config);

        if ($this->library_item !== null) {
            $this->id = (int)$library_item->id;
            $this->library_module_id = (int)$library_item->library_module_id;
            $this->status = (int)$this->library_item->status;
            $this->type = (string)$this->library_item->type;
            $this->url = (string)$this->library_item->url;
            $this->file = (string)$this->library_item->file;
            foreach ($this->library_item->translations as $translation) {
                $this->translations[$translation->lang] = [
                    'title' => $translation->title,
                    'text' => $translation->text,
                ];
            }
        }

        foreach ($this->getLanguages() as $language) {
            $this->translations[$language->code] = array_merge(
                ['title' => '', 'text' => ''],
                $this->translations[$language->code] ?? []
            );
        }
    }

    public function rules(): array
    {
        return [
            [['status', 'library_module_id'], 'required'],
            [
                ['url'],
                'required',
                'when' => function ($model) {
                    return $model->type == LibraryItem::TYPE_URL;
                },
                'whenClient' => "function (attribute, value) {
                    return $('#model-type').val() == '" . LibraryItem::TYPE_URL . "';
                }",
            ],
            [['status'], 'integer'],
            [['type'], 'default', 'value' => 'url'],
            [['type'], 'string'],
            ['type', 'in', 'range' => array_keys(LibraryItem::optsType())],
            [['status'], 'in', 'range' => array_keys(LibraryItem::statusOptions())],
            [['translations'], 'safe'],
            [['translations'], 'validateTranslations'],
            [['libraryItemFile'], 'file', 'skipOnEmpty' => true, 'extensions' => ['pdf', 'docx', 'doc']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'status' => 'Status',
            'type' => 'Type',
            'url' => 'Url',
            'file' => 'File',
            'library_module_id' => 'Library module id',
            'libraryItemFile' => 'file'
        ];
    }

    public function validateTranslations(string $attribute): void
    {
        foreach ($this->getLanguages() as $language) {
            $data = $this->translations[$language->code] ?? [];
            $title = trim((string)($data['title'] ?? ''));
            $text = trim((string)($data['text'] ?? ''));

            if ($title === '') {
                $this->addError("translations[{$language->code}][title]", "Title is required for {$language->name}.");
            }
        }
    }

    public function save(): bool
    {

        if ($this->type === LibraryItem::TYPE_FILE) {
            $this->libraryItemFile = UploadedFile::getInstance($this, 'libraryItemFile');
        }

        if (!$this->validate()) {
            return false;
        }


        $library_item = $this->library_item ?? new LibraryItem();
        $library_item->status = $this->status;
        $library_item->type = $this->type;
        $library_item->library_module_id = $this->library_module_id;
        $library_item->url = $this->url;


        if ($this->type === LibraryItem::TYPE_FILE && $this->libraryItemFile !== null) {
            $library_item->file = $this->saveUpload($this->libraryItemFile);
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (!$library_item->save()) {
                $this->addErrors($library_item->getErrors());
                $transaction->rollBack();

                return false;
            }


            LibraryItemMl::deleteAll(['library_item_id' => $library_item->id]);

            foreach ($this->getLanguages() as $language) {
                $translation = new LibraryItemMl();
                $translation->library_item_id = $library_item->id;
                $translation->lang = $language->code;
                $translation->title = trim((string)$this->translations[$language->code]['title']);
                $translation->text = trim((string)$this->translations[$language->code]['text']);


                if (!$translation->save()) {
                    $this->addErrors($translation->getErrors());
                    $transaction->rollBack();

                    return false;
                }
            }

            $transaction->commit();
            $this->library_item = $library_item;

            return true;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * @return Language[]
     */
    public function getLanguages(): array
    {
        if ($this->_languages === null) {
            $this->_languages = Language::find()
                ->where(['is_active' => 1])
                ->orderBy(['sort_order' => SORT_ASC, 'name' => SORT_ASC])
                ->all();
        }

        return $this->_languages;
    }

    private function deleteUploads(array $paths): void
    {
        $uploadPrefix = '/uploads/education-levels/';
        $basePath = dirname(__DIR__, 4) . '/frontend/web/uploads/education-levels';

        foreach ($paths as $path) {
            if (!is_string($path)) {
                continue;
            }

            $fileName = basename($path);
            if ($path !== $uploadPrefix . $fileName) {
                continue;
            }

            $filePath = $basePath . '/' . $fileName;
            if (is_file($filePath)) {
                @unlink($filePath);
            }
        }
    }

    protected function saveUpload(UploadedFile $file): string
    {
        $basePath = dirname(__DIR__, 4) . '/frontend/web/uploads/library';
        FileHelper::createDirectory($basePath);
        $name = Yii::$app->security->generateRandomString(16) . '.' . $file->extension;
        $file->saveAs($basePath . '/' . $name);
        return '/uploads/library/' . $name;
    }
}
