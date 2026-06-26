<?php

namespace backend\modules\faculties\models;

use common\models\Chairs;
use common\models\ChairsMl;
use common\models\Language;
use Yii;
use yii\base\Model;

class ChairsForm extends Model
{
    public ?Chairs $chairs = null;
    public int $status = Chairs::STATUS_PUBLISHED;
    public int $pos = 1;
    public int $faculty_id = 0;
    public array $translations = [];

    private ?array $_languages = null;

    public function __construct(?Chairs $chairs = null, $config = [])
    {
        $this->chairs = $chairs;
        parent::__construct($config);

        if ($this->chairs !== null) {
            $this->status = (int) $this->chairs->status;
            $this->pos = (int) $this->chairs->pos;
            $this->faculty_id = (int) $this->chairs->faculty_id;
            foreach ($this->chairs->translations as $translation) {
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
            [['status','pos','faculty_id'], 'required'],
            [['status','pos','faculty_id'], 'integer'],
            [['status'], 'in', 'range' => array_keys(Chairs::statusOptions())],
            [['translations'], 'safe'],
            [['translations'], 'validateTranslations'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'status' => 'Status',
            'pos' => 'Position',
            'faculty_id' => 'Faculty',
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

        $chairs = $this->chairs ?? new Chairs();
        $chairs->status = $this->status;
        $chairs->pos = $this->pos;
        $chairs->faculty_id = $this->faculty_id;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$chairs->save()) {
                $this->addErrors($chairs->getErrors());
                $transaction->rollBack();

                return false;
            }

            ChairsMl::deleteAll(['chair_id' => $chairs->id]);

            foreach ($this->getLanguages() as $language) {
                $translation = new ChairsMl();
                $translation->chair_id = $chairs->id;
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
            $this->chairs = $chairs;

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
