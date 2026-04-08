<?php

namespace backend\modules\emailcontent\models;

use common\models\EmailContent;
use common\models\EmailContentMl;
use common\models\Language;
use Yii;
use yii\base\Model;

class EmailContentForm extends Model
{
    public ?EmailContent $emailContent = null;
    public int $status = EmailContent::STATUS_PUBLISHED;
    public array $translations = [];

    private ?array $_languages = null;

    public function __construct(?EmailContent $emailContent = null, $config = [])
    {
        $this->emailContent = $emailContent;
        parent::__construct($config);

        if ($this->emailContent !== null) {
            $this->status = (int) $this->emailContent->status;
            foreach ($this->emailContent->translations as $translation) {
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
            [['status'], 'required'],
            [['status'], 'integer'],
            [['status'], 'in', 'range' => array_keys(EmailContent::statusOptions())],
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
            $text = trim((string) ($data['text'] ?? ''));

            if ($title === '') {
                $this->addError("translations[{$language->code}][title]", "Title is required for {$language->name}.");
            }

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

        $emailContent = $this->emailContent ?? new EmailContent();
        $emailContent->status = $this->status;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$emailContent->save()) {
                $this->addErrors($emailContent->getErrors());
                $transaction->rollBack();

                return false;
            }

            EmailContentMl::deleteAll(['email_content_id' => $emailContent->id]);

            foreach ($this->getLanguages() as $language) {
                $translation = new EmailContentMl();
                $translation->email_content_id = $emailContent->id;
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
            $this->emailContent = $emailContent;

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
