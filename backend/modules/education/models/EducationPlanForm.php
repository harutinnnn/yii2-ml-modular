<?php

namespace backend\modules\education\models;

use common\models\EducationPlan;
use common\models\EducationPlanMl;
use common\models\Language;
use Yii;
use yii\base\DynamicModel;
use yii\base\Model;
use yii\web\UploadedFile;

class EducationPlanForm extends Model
{
    public ?EducationPlan $education_plan = null;
    public int $status = EducationPlan::STATUS_PUBLISHED;
    public int $pos = 0;
    public int $educational_program_id = 0;
    public array $translations = [];

    private ?array $_languages = null;

    public function __construct(?EducationPlan $education_plan = null, $config = [])
    {
        $this->education_plan = $education_plan;
        parent::__construct($config);

        if ($this->education_plan !== null) {
            $this->status = (int)$this->education_plan->status;
            $this->pos = (int)$this->education_plan->pos;
            $this->educational_program_id = (int)$this->education_plan->educational_program_id;
            foreach ($this->education_plan->translations as $translation) {
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
            [['status', 'pos'], 'required'],
            [['status', 'pos','educational_program_id'], 'integer'],
            [['status'], 'in', 'range' => array_keys(EducationPlan::statusOptions())],
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
            $title = trim((string)($data['title'] ?? ''));
            $text = trim((string)($data['text'] ?? ''));

            if ($title === '') {
                $this->addError("translations[{$language->code}][title]", "Title is required for {$language->name}.");
            }

            if ($text === '') {
                $this->addError("translations[{$language->code}][text]", "Text is required for {$language->name}.");
            }
        }
    }

    public function save(int $programId): bool
    {
        if (!$this->validate()) {
            return false;
        }


        $education_plan = $this->education_plan ?? new EducationPlan();
        $education_plan->status = $this->status;
        $education_plan->pos = $this->pos;
        $education_plan->educational_program_id = $programId;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$education_plan->save()) {
                $this->addErrors($education_plan->getErrors());
                $transaction->rollBack();

                return false;
            }


            EducationPlanMl::deleteAll(['educational_plan_id' => $education_plan->id]);

            foreach ($this->getLanguages() as $language) {
                $translation = new EducationPlanMl();
                $translation->educational_plan_id = $education_plan->id;
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
            $this->education_plan = $education_plan;

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
