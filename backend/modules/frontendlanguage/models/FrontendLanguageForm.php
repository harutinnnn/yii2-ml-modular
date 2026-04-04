<?php

namespace backend\modules\frontendlanguage\models;

use common\models\FrontendLanguage;
use common\models\FrontendLanguageMl;
use common\models\Language;
use Yii;
use yii\base\Model;

class FrontendLanguageForm extends Model
{
    public ?FrontendLanguage $frontendLanguage = null;
    public string $key = '';
    public int $status = FrontendLanguage::STATUS_PENDING;
    public string $type = FrontendLanguage::TYPE_CONTENT;
    public array $translations = [];

    private ?array $_languages = null;

    public function __construct(?FrontendLanguage $frontendLanguage = null, $config = [])
    {
        $this->frontendLanguage = $frontendLanguage;
        parent::__construct($config);

        if ($this->frontendLanguage !== null) {
            $this->key = $this->frontendLanguage->key;
            $this->status = (int) $this->frontendLanguage->status;
            $this->type = $this->frontendLanguage->type;
            foreach ($this->frontendLanguage->translations as $translation) {
                $this->translations[$translation->lang] = [
                    'text' => $translation->text,
                ];
            }
        }

        foreach ($this->getLanguages() as $language) {
            $this->translations[$language->code] = array_merge(
                ['text' => ''],
                $this->translations[$language->code] ?? []
            );
        }
    }

    public function rules(): array
    {
        return [
            [['key', 'status', 'type'], 'required'],
            [['status'], 'integer'],
            [['status'], 'in', 'range' => array_keys(FrontendLanguage::statusOptions())],
            [['key'], 'string', 'max' => 255],
            [['type'], 'in', 'range' => array_keys(FrontendLanguage::typeOptions())],
            [['translations'], 'safe'],
            [['translations'], 'validateTranslations'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'key' => 'Key',
            'status' => 'Status',
            'type' => 'Type',
        ];
    }

    public function validateTranslations(string $attribute): void
    {
        foreach ($this->getLanguages() as $language) {
            $data = $this->translations[$language->code] ?? [];
            $text = trim((string) ($data['text'] ?? ''));

            if ($text === '') {
                $this->addError("translations[{$language->code}][text]", "Text is required for {$language->name}.");
            }
        }
    }

    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $frontendLanguage = $this->frontendLanguage ?? new FrontendLanguage();
        $frontendLanguage->key = trim($this->key);
        $frontendLanguage->status = $this->status;
        $frontendLanguage->type = $this->type;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$frontendLanguage->save()) {
                $this->addErrors($frontendLanguage->getErrors());
                $transaction->rollBack();

                return false;
            }

            FrontendLanguageMl::deleteAll(['frontend_language_id' => $frontendLanguage->id]);

            foreach ($this->getLanguages() as $language) {
                $translation = new FrontendLanguageMl();
                $translation->frontend_language_id = $frontendLanguage->id;
                $translation->lang = $language->code;
                $translation->text = trim((string) $this->translations[$language->code]['text']);

                if (!$translation->save()) {
                    $this->addErrors($translation->getErrors());
                    $transaction->rollBack();

                    return false;
                }
            }

            $transaction->commit();
            $this->frontendLanguage = $frontendLanguage;

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
