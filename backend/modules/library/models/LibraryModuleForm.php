<?php

namespace backend\modules\library\models;

use common\components\StatusList;
use common\models\Language;
use common\models\LibraryModule;
use common\models\LibraryModuleMl;
use Yii;
use yii\base\DynamicModel;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class LibraryModuleForm extends Model
{
    public ?LibraryModule $library_module = null;
    public int $status = StatusList::STATUS_ACTIVE;
    public int $id = 0;
    public int $pos = 0;
    public string $slug = '';
    public array $translations = [];

    private ?array $_languages = null;

    public function __construct(?LibraryModule $library_module = null, $config = [])
    {
        $this->library_module = $library_module;
        parent::__construct($config);

        if ($this->library_module !== null) {
            $this->id = (int)$library_module->id;
            $this->status = (int)$this->library_module->status;
            $this->pos = (int)$this->library_module->pos;
            $this->slug = (string)$this->library_module->slug;
            foreach ($this->library_module->translations as $translation) {
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
            [['status', 'pos','slug'], 'required'],
            [['status', 'pos'], 'integer'],
            [['status'], 'in', 'range' => array_keys(LibraryModule::statusOptions())],
            [['translations'], 'safe'],
            [['translations'], 'validateTranslations'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'status' => 'Status',
            'pos' => 'Position',
            'slug' => 'Slug',
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

        if (!$this->validate()) {
            return false;
        }


        $library_module = $this->library_module ?? new LibraryModule();
        $library_module->status = $this->status;
        $library_module->pos = $this->pos;
        $library_module->slug = $this->slug;


        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (!$library_module->save()) {
                $this->addErrors($library_module->getErrors());
                $transaction->rollBack();

                return false;
            }


            LibraryModuleMl::deleteAll(['library_module_id' => $library_module->id]);

            foreach ($this->getLanguages() as $language) {
                $translation = new LibraryModuleMl();
                $translation->library_module_id = $library_module->id;
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
            $this->library_module = $library_module;

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
}
