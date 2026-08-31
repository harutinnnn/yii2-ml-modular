<?php

namespace backend\modules\education\models;

use common\models\EducationalPrograms;
use common\models\EducationalProgramsMl;
use common\models\Language;
use Yii;
use yii\base\DynamicModel;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class EducationalProgramsForm extends Model
{
    private const DOCUMENT_UPLOAD_FIELDS = [
        'docAboutProgramFile' => 'doc_about_program',
        'docEducationalProgramGuideFile' => 'doc_educational_program_guide',
        'docSubjectListFile' => 'doc_subject_list',
        'docSubjectListRemoteFile' => 'doc_subject_list_remote',
    ];

    public ?EducationalPrograms $education_programs = null;
    public int $status = EducationalPrograms::STATUS_PUBLISHED;
    public int $education_level = 0;
    public float $duration_by_year = 0;
    public int $is_remote = 0;
    public array $translations = [];


    private ?array $_languages = null;
    private array $_documents = [];

    public function __construct(?EducationalPrograms $education_programs = null, $config = [])
    {
        $this->education_programs = $education_programs;
        parent::__construct($config);

        if ($this->education_programs !== null) {
            $this->status = (int)$this->education_programs->status;
            $this->duration_by_year = (float)$this->education_programs->duration_by_year;
            $this->education_level = (int)$this->education_programs->education_level;
            $this->is_remote = (int)$this->education_programs->is_remote;
            foreach ($this->education_programs->translations as $translation) {
                $this->translations[$translation->lang] = [
                    'title' => $translation->title,
                    'desc' => $translation->desc,
                ];
                foreach (self::DOCUMENT_UPLOAD_FIELDS as $documentAttribute) {
                    $this->_documents[$translation->lang][$documentAttribute] = $translation->$documentAttribute;
                }
            }
        }

        foreach ($this->getLanguages() as $language) {
            $this->translations[$language->code] = array_merge(
                ['title' => '', 'desc' => ''],
                $this->translations[$language->code] ?? []
            );
        }
    }

    public function rules(): array
    {
        return [
            [['status','duration_by_year','education_level'], 'required'],
            [['duration_by_year'], 'number'],
            [['status','education_level','is_remote'], 'integer'],
            [['status'], 'in', 'range' => array_keys(EducationalPrograms::statusOptions())],
            [['translations'], 'safe'],
            [['translations'], 'validateTranslations'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'status' => 'Status',
            'duration_by_year' => 'Duration by year',
            'education_level' => 'Education level',
            'is_remote' => 'Is remote',
            'doc_about_program' => 'Document about program',
            'doc_educational_program_guide' => 'Document educational program guide',
            'doc_subject_list' => 'Document subject list',
            'doc_subject_list_remote' => 'Document subject list remote',
        ];
    }

    public function validateTranslations(string $attribute): void
    {
        foreach ($this->getLanguages() as $language) {
            $data = $this->translations[$language->code] ?? [];
            $title = trim((string)($data['title'] ?? ''));
            $desc = trim((string)($data['desc'] ?? ''));

            if ($title === '') {
                $this->addError("translations[{$language->code}][title]", "Title is required for {$language->name}.");
            }

            if ($desc === '') {
                $this->addError("translations[{$language->code}][desc]", "Description is required for {$language->name}.");
            }

            foreach (self::DOCUMENT_UPLOAD_FIELDS as $uploadAttribute => $documentAttribute) {
                $file = $data[$uploadAttribute] ?? null;
                if ($file instanceof UploadedFile) {
                    $fileModel = DynamicModel::validateData(['file' => $file], [
                        [['file'], 'file', 'extensions' => ['pdf'], 'mimeTypes' => ['application/pdf']],
                    ]);

                    if ($fileModel->hasErrors('file')) {
                        $this->addError(
                            "translations[{$language->code}][{$uploadAttribute}]",
                            $fileModel->getFirstError('file')
                        );
                    }
                }
            }
        }
    }

    public function save(): bool
    {
        foreach ($this->getLanguages() as $language) {
            foreach (self::DOCUMENT_UPLOAD_FIELDS as $uploadAttribute => $documentAttribute) {
                $attribute = "translations[{$language->code}][{$uploadAttribute}]";
                $this->translations[$language->code][$uploadAttribute] = UploadedFile::getInstance($this, $attribute);
            }
        }

        if (!$this->validate()) {
            return false;
        }


        $education_programs = $this->education_programs ?? new EducationalPrograms();
        $education_programs->status = $this->status;
        $education_programs->duration_by_year = $this->duration_by_year;
        $education_programs->education_level = $this->education_level;
        $education_programs->is_remote = $this->is_remote;

        $filesToDeleteAfterCommit = [];
        $newUploads = [];
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$education_programs->save()) {
                $this->addErrors($education_programs->getErrors());
                $transaction->rollBack();

                return false;
            }


            EducationalProgramsMl::deleteAll(['educational_program_id' => $education_programs->id]);

            foreach ($this->getLanguages() as $language) {
                $translation = new EducationalProgramsMl();
                $translation->educational_program_id = $education_programs->id;
                $translation->lang = $language->code;
                $translation->title = trim((string)$this->translations[$language->code]['title']);
                $translation->desc = trim((string)$this->translations[$language->code]['desc']);
                foreach (self::DOCUMENT_UPLOAD_FIELDS as $uploadAttribute => $documentAttribute) {
                    $existingDocument = $this->_documents[$language->code][$documentAttribute] ?? null;
                    $translation->$documentAttribute = $existingDocument;

                    $file = $this->translations[$language->code][$uploadAttribute] ?? null;
                    if ($file instanceof UploadedFile) {
                        $translation->$documentAttribute = $this->saveUpload($file);
                        $newUploads[] = $translation->$documentAttribute;

                        if ($existingDocument !== null) {
                            $filesToDeleteAfterCommit[] = $existingDocument;
                        }
                    } elseif (!empty($this->translations[$language->code][$uploadAttribute . 'Remove'])) {
                        $translation->$documentAttribute = null;

                        if ($existingDocument !== null) {
                            $filesToDeleteAfterCommit[] = $existingDocument;
                        }
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
            $this->education_programs = $education_programs;
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

    public function getDocument(string $languageCode, string $documentAttribute): ?string
    {
        if (!in_array($documentAttribute, self::DOCUMENT_UPLOAD_FIELDS, true)) {
            return null;
        }

        return $this->_documents[$languageCode][$documentAttribute] ?? null;
    }

    protected function saveUpload(UploadedFile $file): string
    {
        $basePath = dirname(__DIR__, 4) . '/frontend/web/uploads/educational-programs';
        FileHelper::createDirectory($basePath);
        $name = Yii::$app->security->generateRandomString(16) . '.pdf';

        if (!$file->saveAs($basePath . '/' . $name)) {
            throw new \RuntimeException('Unable to save the uploaded PDF.');
        }

        return '/uploads/educational-programs/' . $name;
    }

    private function deleteUploads(array $paths): void
    {
        $uploadPrefix = '/uploads/educational-programs/';
        $basePath = dirname(__DIR__, 4) . '/frontend/web/uploads/educational-programs';

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
