<?php

namespace backend\modules\news\models;

use common\components\StatusList;
use common\models\Language;
use common\models\NewsTags;
use common\models\NewsTagsMl;
use Yii;
use yii\base\Model;

class NewsTagsForm extends Model
{
    public ?NewsTags $news_tags = null;
    public int $status = StatusList::STATUS_PUBLISHED;
    public array $translations = [];

    private ?array $_languages = null;

    public function __construct(?NewsTags $news_tags = null, $config = [])
    {
        $this->news_tags = $news_tags;
        parent::__construct($config);

        if ($this->news_tags !== null) {
            $this->status = (int) $this->news_tags->status;
            foreach ($this->news_tags->translations as $translation) {
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
            [['status'], 'in', 'range' => array_keys(NewsTags::statusOptions())],
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


        $news_tags = $this->news_tags ?? new NewsTags();
        $news_tags->status = $this->status;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$news_tags->save()) {
                $this->addErrors($news_tags->getErrors());
                $transaction->rollBack();

                return false;
            }


            NewsTagsMl::deleteAll(['tag_id' => $news_tags->id]);

            foreach ($this->getLanguages() as $language) {
                $translation = new NewsTagsMl();
                $translation->tag_id = $news_tags->id;
                $translation->lang = $language->code;
                $translation->title = trim((string) $this->translations[$language->code]['title']);

                if (!$translation->save()) {
                    $this->addErrors($translation->getErrors());
                    $transaction->rollBack();

                    return false;
                }
            }

            $transaction->commit();
            $this->news_tags = $news_tags;

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
