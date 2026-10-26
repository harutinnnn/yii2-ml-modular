<?php

namespace backend\modules\content\models;

use common\models\Language;
use common\models\PageBlockItem;
use common\models\PageBlockItemMl;
use Yii;
use yii\base\Model;

class PageBlockItemForm extends Model
{
    public ?PageBlockItem $page_block_item = null;

    public int $pos = 0;

    public array $translations = [];

    private ?array $_languages = null;

    public function __construct(?PageBlockItem $page_block_item = null, $config = [])
    {
        $this->page_block_item = $page_block_item;
        parent::__construct($config);

        if ($this->page_block_item !== null) {

            $this->pos = (int)$this->page_block_item->pos;

            foreach ($this->page_block_item->translations as $translation) {
                $this->translations[$translation->lang] = [
                    'title' => $translation->title,
                    'text' => $translation->text,
                    'url' => $translation->url,
                ];
            }
        }

        foreach ($this->getLanguages() as $language) {
            $this->translations[$language->code] = array_merge(
                ['title' => '', 'text' => '','url' => ''],
                $this->translations[$language->code] ?? []
            );
        }
    }

    public function rules(): array
    {
        return [
            [['pos'], 'integer', 'min' => 0],
            [['pos'], 'required'],
            [['translations'], 'safe'],
            [['translations'], 'validateTranslations'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'pos' => 'Position'
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

    public function save($page_block_id = 0): bool
    {

        if (!$this->validate()) {
            return false;
        }



        $page_block_item = $this->page_block_item ?? new PageBlockItem();
        $page_block_item->page_block_id = $page_block_id;
        $page_block_item->pos = $this->pos;


        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$page_block_item->save()) {
                $this->addErrors($page_block_item->getErrors());
                $transaction->rollBack();
                return false;
            }

            PageBlockItemMl::deleteAll(['page_block_item_id' => $page_block_item->id]);

            foreach ($this->getLanguages() as $language) {
                $translation = new PageBlockItemMl();
                $translation->page_block_item_id = $page_block_item->id;
                $translation->lang = $language->code;
                $translation->title = trim((string)$this->translations[$language->code]['title']);
                $translation->text = trim((string)$this->translations[$language->code]['text']);
                $translation->url = trim((string)$this->translations[$language->code]['url']);

                if (!$translation->save()) {
                    $this->addErrors($translation->getErrors());
                    $transaction->rollBack();

                    return false;
                }

            }

            $transaction->commit();
            $this->page_block_item = $page_block_item;

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
