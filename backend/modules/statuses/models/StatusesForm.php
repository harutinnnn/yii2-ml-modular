<?php

namespace backend\modules\statuses\models;

use common\models\Language;
use common\models\Statuses;
use common\models\StatusesMl;
use Yii;
use yii\base\Model;

class StatusesForm extends Model
{
    public ?Statuses $statuses = null;
    public int $status = Statuses::STATUS_PUBLISHED;
    public array $translations = [];

    private ?array $_languages = null;

    public function __construct(?Statuses $statuses = null, $config = [])
    {
        $this->statuses = $statuses;
        parent::__construct($config);

        if ($this->statuses !== null) {
            $this->status = (int) $this->statuses->status;

            foreach ($this->statuses->translations as $translation) {
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
            [['status'], 'in', 'range' => array_keys(Statuses::statusOptions())],
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

        $statuses = $this->statuses ?? new Statuses();
        $statuses->status = $this->status;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$statuses->save()) {
                $this->addErrors($statuses->getErrors());
                $transaction->rollBack();

                return false;
            }

            StatusesMl::deleteAll(['status_id' => $statuses->id]);

            foreach ($this->getLanguages() as $language) {
                $translation = new StatusesMl();
                $translation->status_id = $statuses->id;
                $translation->lang = $language->code;
                $translation->title = trim((string) $this->translations[$language->code]['title']);

                if (!$translation->save()) {
                    $this->addErrors($translation->getErrors());
                    $transaction->rollBack();

                    return false;
                }
            }

            $transaction->commit();
            $this->statuses = $statuses;

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
