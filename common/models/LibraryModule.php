<?php

namespace common\models;

use common\components\StatusList;
use Yii;

/**
 * This is the model class for table "library_module".
 *
 * @property int $id
 * @property int $status
 * @property int $pos
 * @property string|null $created_at
 *
 * @property LibraryModuleMl[] $translations
 */
class LibraryModule extends TranslationModel
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'library_module';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'default', 'value' => 0],
            [['status', 'pos'], 'integer'],
            [['pos'], 'required'],
            [['created_at'], 'safe'],
            [['status'], 'in', 'range' => array_keys(self::statusOptions())],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'pos' => 'Pos',
            'created_at' => 'Created At',
        ];
    }

    public function getTranslations()
    {
        return $this->hasMany(LibraryModuleMl::class, ['library_module_id' => 'id'])->indexBy('lang');
    }



    public function getTranslation(string $lang): ?LibraryModuleMl
    {
        $translations = $this->translations;

        return $translations[$lang] ?? null;
    }

    public static function statusOptions(): array
    {
        return [
            StatusList::STATUS_PENDING => 'Pending',
            StatusList::STATUS_ACTIVE => 'Published'
        ];
    }
}
