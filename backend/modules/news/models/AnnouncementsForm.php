<?php

namespace backend\modules\news\models;

use common\models\Announcements;
use common\models\AnnouncementsMl;
use common\models\Language;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class AnnouncementsForm extends Model
{
    public ?Announcements $announcements = null;
    public int $status = Announcements::STATUS_PUBLISHED;
    public int $category_id = 0;
    public string|null $date = null;
    public ?string $image = null;
    public array $translations = [];
    public $imageFile = null;

    private ?array $_languages = null;

    public function __construct(?Announcements $announcements = null, $config = [])
    {
        $this->announcements = $announcements;
        parent::__construct($config);

        if ($this->announcements !== null) {
            $this->status = (int)$this->announcements->status;
            $this->category_id = (int)$this->announcements->category_id;
            $this->date = (string)$this->announcements->date;
            $this->image = $this->announcements->image;
            foreach ($this->announcements->translations as $translation) {
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
            [['status', 'category_id','date'], 'required'],
            [['status', 'category_id'], 'integer'],
            [['date'], 'string'],
            [['status'], 'in', 'range' => array_keys(Announcements::statusOptions())],
            [['translations'], 'safe'],
            [['translations'], 'validateTranslations'],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => ['png', 'jpg', 'jpeg', 'gif', 'webp']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'status' => 'Status',
            'category_id' => 'Category',
            'date' => 'Date',
            'imageFile' => 'Image',
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

    public function save(): bool
    {
        $this->imageFile = UploadedFile::getInstance($this, 'imageFile');

        if (!$this->validate()) {
            return false;
        }

        $announcements = $this->announcements ?? new Announcements();
        $announcements->status = $this->status;
        $announcements->category_id = $this->category_id;
        $announcements->date = $this->date;

        if ($this->imageFile !== null) {
            $announcements->image = $this->saveUpload($this->imageFile);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$announcements->save()) {
                $this->addErrors($announcements->getErrors());
                $transaction->rollBack();

                return false;
            }

            AnnouncementsMl::deleteAll(['announcements_id' => $announcements->id]);

            foreach ($this->getLanguages() as $language) {
                $translation = new AnnouncementsMl();
                $translation->announcements_id = $announcements->id;
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
            $this->announcements = $announcements;

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

    protected function saveUpload(UploadedFile $file): string
    {
        $basePath = dirname(__DIR__, 4) . '/frontend/web/uploads/announcements';
        FileHelper::createDirectory($basePath);
        $name = Yii::$app->security->generateRandomString(16) . '.' . $file->extension;
        $file->saveAs($basePath . '/' . $name);

        return '/uploads/announcements/' . $name;
    }
}
