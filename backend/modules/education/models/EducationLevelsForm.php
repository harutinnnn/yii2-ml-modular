<?php

namespace backend\modules\education\models;

use common\models\Language;
use common\models\EducationLevels;
use common\models\EducationLevelsMl;
use Yii;
use yii\base\DynamicModel;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class EducationLevelsForm extends Model
{
    public ?EducationLevels $education_levels = null;
    public int $status = EducationLevels::STATUS_PUBLISHED;
    public int $pos = 0;
    public array $translations = [];

    private ?array $_languages = null;
    private array $_images = [];

    public function __construct(?EducationLevels $education_levels = null, $config = [])
    {
        $this->education_levels = $education_levels;
        parent::__construct($config);

        if ($this->education_levels !== null) {
            $this->status = (int) $this->education_levels->status;
            $this->pos = (int) $this->education_levels->pos;
            foreach ($this->education_levels->translations as $translation) {
                $this->translations[$translation->lang] = [
                    'title' => $translation->title,
                ];
                $this->_images[$translation->lang] = $translation->img;
            }
        }

        foreach ($this->getLanguages() as $language) {
            $this->translations[$language->code] = array_merge(
                ['title' => ''],
                $this->translations[$language->code] ?? []
            );
        }
    }

    public function rules(): array
    {
        return [
            [['status','pos'], 'required'],
            [['status','pos'], 'integer'],
            [['status'], 'in', 'range' => array_keys(EducationLevels::statusOptions())],
            [['translations'], 'safe'],
            [['translations'], 'validateTranslations'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'status' => 'Status',
            'pos' => 'Position',
        ];
    }

    public function validateTranslations(string $attribute): void
    {
        foreach ($this->getLanguages() as $language) {
            $data = $this->translations[$language->code] ?? [];
            $title = trim((string) ($data['title'] ?? ''));

            if ($title === '') {
                $this->addError("translations[{$language->code}][title]", "Title is required for {$language->name}.");
            }

            $file = $data['imgFile'] ?? null;
            if ($file instanceof UploadedFile) {
                $fileModel = DynamicModel::validateData(['file' => $file], [
                    [['file'], 'file', 'extensions' => ['png', 'jpg', 'jpeg', 'gif', 'webp']],
                ]);

                if ($fileModel->hasErrors('file')) {
                    $this->addError(
                        "translations[{$language->code}][imgFile]",
                        $fileModel->getFirstError('file')
                    );
                }
            }
        }
    }

    public function save(): bool
    {
        foreach ($this->getLanguages() as $language) {
            $attribute = "translations[{$language->code}][imgFile]";
            $this->translations[$language->code]['imgFile'] = UploadedFile::getInstance($this, $attribute);
        }

        if (!$this->validate()) {
            return false;
        }


        $education_levels = $this->education_levels ?? new EducationLevels();
        $education_levels->status = $this->status;
        $education_levels->pos = $this->pos;

        $filesToDeleteAfterCommit = [];
        $newUploads = [];
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$education_levels->save()) {
                $this->addErrors($education_levels->getErrors());
                $transaction->rollBack();

                return false;
            }


            EducationLevelsMl::deleteAll(['education_level_id' => $education_levels->id]);

            foreach ($this->getLanguages() as $language) {
                $translation = new EducationLevelsMl();
                $translation->education_level_id = $education_levels->id;
                $translation->lang = $language->code;
                $translation->title = trim((string) $this->translations[$language->code]['title']);
                $existingImage = $this->_images[$language->code] ?? null;
                $translation->img = $existingImage;

                $file = $this->translations[$language->code]['imgFile'] ?? null;
                if ($file instanceof UploadedFile) {
                    $translation->img = $this->saveUpload($file);
                    $newUploads[] = $translation->img;

                    if ($existingImage !== null) {
                        $filesToDeleteAfterCommit[] = $existingImage;
                    }
                }

                if (!$translation->save()) {
                    $this->addErrors($translation->getErrors());
                    $transaction->rollBack();
                    $this->deleteUploads($newUploads);

                    return false;
                }
            }

            $transaction->commit();
            $this->education_levels = $education_levels;
            $this->deleteUploads(array_unique($filesToDeleteAfterCommit));

            return true;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            $this->deleteUploads($newUploads);
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

    public function getImage(string $languageCode): ?string
    {
        return $this->_images[$languageCode] ?? null;
    }

    protected function saveUpload(UploadedFile $file): string
    {
        $basePath = dirname(__DIR__, 4) . '/frontend/web/uploads/education-levels';
        FileHelper::createDirectory($basePath);
        $name = Yii::$app->security->generateRandomString(16) . '.' . $file->extension;

        if (!$file->saveAs($basePath . '/' . $name)) {
            throw new \RuntimeException('Unable to save the uploaded image.');
        }

        return '/uploads/education-levels/' . $name;
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
