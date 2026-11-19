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
    public string|null $registration_close_date = null;
    public string|null $time = null;
    public int|null $event_places = null;
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
            $this->registration_close_date = (string)$this->events->registration_close_date;
            $this->time = (string)$this->events->time;
            $this->event_places = (int)$this->events->event_places;
            $this->image = $this->events->image;
            foreach ($this->events->translations as $translation) {
                $this->translations[$translation->lang] = [
                    'title' => $translation->title,
                    'text' => $translation->text,
                    'place' => $translation->place,
                    'organiser' => $translation->organiser,
                    'contact' => $translation->contact,
                    'topic' => $translation->topic,
                ];
            }
        }

        foreach ($this->getLanguages() as $language) {
            $this->translations[$language->code] = array_merge(
                ['title' => '', 'text' => '', 'place' => '', 'organiser' => '', 'contact' => '','topic' => ''],
                $this->translations[$language->code] ?? []
            );
        }
    }

    public function rules(): array
    {
        return [
            [['status', 'date', 'time'], 'required'],
            [['status','event_places'], 'integer'],
            [['date', 'time','registration_close_date'], 'string'],
            [['status'], 'in', 'range' => array_keys(Events::statusOptions())],
            [['translations'], 'safe'],
            [['translations'], 'validateTranslations', 'skipOnEmpty' => false],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => ['png', 'jpg', 'jpeg', 'gif', 'webp']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'status' => 'Status',
            'date' => 'Date',
            'registration_close_date' => 'Registration close date',
            'event_places' => 'Event places',
            'time' => 'Time',
            'imageFile' => 'Image',
        ];
    }

    public function validateTranslations(string $attribute): void
    {
        if ($this->getCompleteTranslations() === []) {
            $this->addError($attribute, 'Fill in the title, text, place, organiser, topic and contact in at least one language.');
        }
    }

    private function getCompleteTranslations(): array
    {
        $translations = [];
        foreach ($this->getLanguages() as $language) {
            $data = $this->translations[$language->code] ?? [];
            $title = is_string($data['title'] ?? null) ? trim($data['title']) : '';
            $text = is_string($data['text'] ?? null) ? trim($data['text']) : '';
            $place = is_string($data['place'] ?? null) ? trim($data['place']) : '';
            $organiser = is_string($data['organiser'] ?? null) ? trim($data['organiser']) : '';
            $contact = is_string($data['contact'] ?? null) ? trim($data['contact']) : '';
            $topic = is_string($data['topic'] ?? null) ? trim($data['topic']) : '';
            $editorData = json_decode($text, true);

            if ($title !== '' && $text !== '' && $place !== '' && $organiser !== '' && $contact !== '' && $topic !== ''
                && (!is_array($editorData) || !empty($editorData['blocks']))) {
                $translations[$language->code] = [
                    'title' => $title,
                    'text' => $text,
                    'place' => $place,
                    'organiser' => $organiser,
                    'contact' => $contact,
                    'topic' => $topic,
                ];
            }
        }

        return $translations;
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
        $events->registration_close_date = $this->registration_close_date;
        $events->time = $this->time;
        $events->event_places = $this->event_places;

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

            $translations = $this->getCompleteTranslations();
            EventsMl::deleteAll(['events_id' => $events->id, 'lang' => array_keys($translations)]);

            foreach ($translations as $lang => $data) {
                $translation = new EventsMl();
                $translation->events_id = $events->id;
                $translation->lang = $lang;
                $translation->title = $data['title'];
                $translation->text = $data['text'];
                $translation->place = $data['place'];
                $translation->organiser = $data['organiser'];
                $translation->contact = $data['contact'];
                $translation->topic = $data['topic'];

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
