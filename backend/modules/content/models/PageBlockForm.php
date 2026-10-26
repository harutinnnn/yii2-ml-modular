<?php

namespace backend\modules\content\models;

use common\components\StatusList;
use common\models\Language;
use common\models\PageBlock;
use common\models\PageBlockMl;
use Yii;
use yii\base\Model;

class PageBlockForm extends Model
{
    public ?PageBlock $page_block = null;
    public int $status = StatusList::STATUS_ACTIVE;
    public string $type = PageBlock::TYPE_CART;
    public array $translations = [];

    private ?array $_languages = null;

    public function __construct(?PageBlock $page_block = null, $config = [])
    {
        $this->page_block = $page_block;
        parent::__construct($config);

        if ($this->page_block !== null) {
            $this->status = (int)$this->page_block->status;
            $this->type = (int)$this->page_block->type;
            foreach ($this->page_block->translations as $translation) {
                $this->translations[$translation->lang] = [
                    'title' => $translation->title,
                    'pre_title' => $translation->pre_title,
                ];
            }
        }

        foreach ($this->getLanguages() as $language) {
            $this->translations[$language->code] = array_merge(
                ['title' => '', 'pre_title' => ''],
                $this->translations[$language->code] ?? []
            );
        }
    }

    public function rules(): array
    {
        return [
            [['status', 'type'], 'required'],
            [['status'], 'integer'],
            [['status'], 'in', 'range' => array_keys(PageBlock::statusOptions())],
            [['type'], 'in', 'range' => array_keys(PageBlock::typeOptions())],
            [['translations'], 'safe'],
            [['translations'], 'validateTranslations'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'status' => 'Status',
            'type' => 'Type',
        ];
    }

    public function validateTranslations(string $attribute): void
    {
        foreach ($this->getLanguages() as $language) {
            $data = $this->translations[$language->code] ?? [];
            $title = trim((string)($data['title'] ?? ''));
            $pre_title = trim((string)($data['pre_title'] ?? ''));

            if ($title === '') {
                $this->addError("translations[{$language->code}][title]", "Title is required for {$language->name}.");
            }
            if ($pre_title === '') {
                $this->addError("translations[{$language->code}][pre_title]", "Pre title is required for {$language->name}.");
            }
        }
    }

    public function save(): bool
    {

        if (!$this->validate()) {
            return false;
        }

        $page_block = $this->page_block ?? new PageBlock();
        $page_block->status = $this->status;
        $page_block->type = $this->type;


        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$page_block->save()) {
                $this->addErrors($page_block->getErrors());
                $transaction->rollBack();

                return false;
            }

            PageBlockMl::deleteAll(['page_block_id' => $page_block->id]);

            foreach ($this->getLanguages() as $language) {
                $translation = new PageBlockMl();
                $translation->page_block_id = $page_block->id;
                $translation->lang = $language->code;
                $translation->title = trim((string)$this->translations[$language->code]['title']);
                $translation->pre_title = trim((string)$this->translations[$language->code]['pre_title']);

                if (!$translation->save()) {
                    $this->addErrors($translation->getErrors());
                    $transaction->rollBack();

                    return false;
                }
            }

            $transaction->commit();
            $this->page_block = $page_block;

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
