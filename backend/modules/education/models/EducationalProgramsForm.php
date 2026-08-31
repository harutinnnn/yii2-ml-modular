<?php

namespace backend\modules\education\models;

use common\models\EducationalPrograms;
use common\models\EducationalProgramsMl;
use common\models\Language;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class EducationalProgramsForm extends Model
{
    public ?EducationalPrograms $education_programs = null;
    public int $status = EducationalPrograms::STATUS_PUBLISHED;
    public int $education_level = 0;
    public float $duration_by_year = 0;
    public int $is_remote = 0;
    public array $translations = [];

    private ?array $_languages = null;

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
        }
    }

    public function save(): bool
    {

        if (!$this->validate()) {
            return false;
        }


        $education_programs = $this->education_programs ?? new EducationalPrograms();
        $education_programs->status = $this->status;
        $education_programs->duration_by_year = $this->duration_by_year;
        $education_programs->education_level = $this->education_level;
        $education_programs->is_remote = $this->is_remote;

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

                if (!$translation->save()) {
                    $this->addErrors($translation->getErrors());
                    $transaction->rollBack();

                    return false;
                }
            }

            $transaction->commit();
            $this->education_programs = $education_programs;

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

    protected function saveUpload(UploadedFile $file): string
    {
        $basePath = dirname(__DIR__, 4) . '/frontend/web/uploads/' . EducationalPrograms::tableName();
        FileHelper::createDirectory($basePath);
        $name = Yii::$app->security->generateRandomString(16) . '.' . $file->extension;
        $file->saveAs($basePath . '/' . $name);

        return '/uploads/' . EducationalPrograms::tableName() . '/' . $name;
    }
}
