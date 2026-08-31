<?php

namespace backend\modules\education\models;

use common\models\Language;
use common\models\EducationLevels;
use common\models\EducationLevelsMl;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class EducationLevelsForm extends Model
{
    public ?EducationLevels $education_levels = null;
    public int $status = EducationLevels::STATUS_PUBLISHED;
    public array $translations = [];

    private ?array $_languages = null;

    public function __construct(?EducationLevels $education_levels = null, $config = [])
    {
        $this->education_levels = $education_levels;
        parent::__construct($config);

        if ($this->education_levels !== null) {
            $this->status = (int) $this->education_levels->status;
            foreach ($this->education_levels->translations as $translation) {
                $this->translations[$translation->lang] = [
                    'title' => $translation->title,
                ];
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
            [['status'], 'required'],
            [['status'], 'integer'],
            [['status'], 'in', 'range' => array_keys(EducationLevels::statusOptions())],
            [['translations'], 'safe'],
            [['translations'], 'validateTranslations'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'status' => 'Status',
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
        }
    }

    public function save(): bool
    {

        if (!$this->validate()) {
            return false;
        }


        $education_levels = $this->education_levels ?? new EducationLevels();
        $education_levels->status = $this->status;

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

                if (!$translation->save()) {
                    $this->addErrors($translation->getErrors());
                    $transaction->rollBack();

                    return false;
                }
            }

            $transaction->commit();
            $this->education_levels = $education_levels;

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
}
