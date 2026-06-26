<?php

namespace backend\modules\faculties\models;

use common\models\Faculties;
use common\models\FacultiesMl;
use common\models\Language;
use Yii;
use yii\base\Model;

class FacultiesForm extends Model
{
    public ?Faculties $faculties = null;
    public int $status = Faculties::STATUS_PUBLISHED;
    public int $pos = 1;
    public array $translations = [];

    private ?array $_languages = null;

    public function __construct(?Faculties $faculties = null, $config = [])
    {
        $this->faculties = $faculties;
        parent::__construct($config);

        if ($this->faculties !== null) {
            $this->status = (int) $this->faculties->status;
            $this->pos = (int) $this->faculties->pos;
            foreach ($this->faculties->translations as $translation) {
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
            [['status','pos'], 'required'],
            [['status','pos'], 'integer'],
            [['status'], 'in', 'range' => array_keys(Faculties::statusOptions())],
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
//            $text = trim((string) ($data['text'] ?? ''));

            if ($title === '') {
                $this->addError("translations[{$language->code}][title]", "Title is required for {$language->name}.");
            }

//            if ($text === '') {
//                $this->addError("translations[{$language->code}][text]", "Text is required for {$language->name}.");
//            }
        }
    }

    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $faculties = $this->faculties ?? new Faculties();
        $faculties->status = $this->status;
        $faculties->pos = $this->pos;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$faculties->save()) {
                $this->addErrors($faculties->getErrors());
                $transaction->rollBack();

                return false;
            }

            FacultiesMl::deleteAll(['faculty_id' => $faculties->id]);

            foreach ($this->getLanguages() as $language) {
                $translation = new FacultiesMl();
                $translation->faculty_id = $faculties->id;
                $translation->lang = $language->code;
                $translation->title = trim((string) $this->translations[$language->code]['title']);
                $translation->text = trim((string) $this->translations[$language->code]['text']);

                if (!$translation->save()) {
                    $this->addErrors($translation->getErrors());
                    $transaction->rollBack();

                    return false;
                }
            }

            $transaction->commit();
            $this->faculties = $faculties;

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
