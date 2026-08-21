<?php

namespace backend\modules\news\models;

use common\models\Events;
use common\models\EventsMl;
use common\models\Language;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class EventsForm extends Model
{
    public ?Events $events = null;
    public int $status = Events::STATUS_PUBLISHED;
    public string|null $date = null;
    public string|null $time = null;
    public ?string $image = null;
    public array $translations = [];
    public $imageFile = null;

    private ?array $_languages = null;

    public function __construct(?Events $events = null, $config = [])
    {
        $this->events = $events;
        parent::__construct($config);

        if ($this->events !== null) {
            $this->status = (int)$this->events->status;
            $this->date = (string)$this->events->date;
            $this->time = (string)$this->events->time;
            $this->image = $this->events->image;
            foreach ($this->events->translations as $translation) {
                $this->translations[$translation->lang] = [
                    'title' => $translation->title,
                    'text' => $translation->text,
                    'place' => $translation->place,
                    'organiser' => $translation->organiser,
                    'contact' => $translation->contact,
                ];
            }
        }

        foreach ($this->getLanguages() as $language) {
            $this->translations[$language->code] = array_merge(
                ['title' => '', 'text' => '', 'place' => '', 'organiser' => '', 'contact' => ''],
                $this->translations[$language->code] ?? []
            );
        }
    }

    public function rules(): array
    {
        return [
            [['status', 'date', 'time'], 'required'],
            [['status'], 'integer'],
            [['date', 'time'], 'string'],
            [['status'], 'in', 'range' => array_keys(Events::statusOptions())],
            [['translations'], 'safe'],
            [['translations'], 'validateTranslations'],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => ['png', 'jpg', 'jpeg', 'gif', 'webp']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'status' => 'Status',
            'date' => 'Date',
            'time' => 'Time',
            'imageFile' => 'Image',
        ];
    }

    public function validateTranslations(string $attribute): void
    {
        foreach ($this->getLanguages() as $language) {
            $data = $this->translations[$language->code] ?? [];
            $title = trim((string)($data['title'] ?? ''));
            $text = trim((string)($data['text'] ?? ''));
            $place = trim((string)($data['place'] ?? ''));
            $organiser = trim((string)($data['organiser'] ?? ''));
            $contact = trim((string)($data['contact'] ?? ''));

            if ($title === '') {
                $this->addError("translations[{$language->code}][title]", "Title is required for {$language->name}.");
            }

            if ($text === '') {
                $this->addError("translations[{$language->code}][text]", "Text is required for {$language->name}.");
            }

            if ($place === '') {
                $this->addError("translations[{$language->code}][place]", "Place is required for {$language->name}.");
            }

            if ($organiser === '') {
                $this->addError("translations[{$language->code}][organiser]", "Organiser is required for {$language->name}.");
            }

            if ($contact === '') {
                $this->addError("translations[{$language->code}][contact]", "Contact is required for {$language->name}.");
            }
        }
    }

    public function save(): bool
    {
        $this->imageFile = UploadedFile::getInstance($this, 'imageFile');

        if (!$this->validate()) {
            return false;
        }

        $events = $this->events ?? new Events();
        $events->status = $this->status;
        $events->date = $this->date;
        $events->time = $this->time;

        if ($this->imageFile !== null) {
            $events->image = $this->saveUpload($this->imageFile);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$events->save()) {
                $this->addErrors($events->getErrors());
                $transaction->rollBack();

                return false;
            }

            EventsMl::deleteAll(['events_id' => $events->id]);

            foreach ($this->getLanguages() as $language) {
                $translation = new EventsMl();
                $translation->events_id = $events->id;
                $translation->lang = $language->code;
                $translation->title = trim((string)$this->translations[$language->code]['title']);
                $translation->text = trim((string)$this->translations[$language->code]['text']);
                $translation->place = trim((string)$this->translations[$language->code]['place']);
                $translation->organiser = trim((string)$this->translations[$language->code]['organiser']);
                $translation->contact = trim((string)$this->translations[$language->code]['contact']);

                if (!$translation->save()) {
                    $this->addErrors($translation->getErrors());
                    $transaction->rollBack();

                    return false;
                }
            }

            $transaction->commit();
            $this->events = $events;

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
        $basePath = dirname(__DIR__, 4) . '/frontend/web/uploads/events';
        FileHelper::createDirectory($basePath);
        $name = Yii::$app->security->generateRandomString(16) . '.' . $file->extension;
        $file->saveAs($basePath . '/' . $name);

        return '/uploads/events/' . $name;
    }
}
