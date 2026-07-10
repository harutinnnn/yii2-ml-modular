<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "journal_numbers".
 *
 * @property int $id
 * @property int $journal_id
 * @property int $year
 * @property int $number
 * @property string|null $status
 *
 * @property Journal $journal
 */
class JournalNumbers extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'journal_numbers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'default', 'value' => 'inactive'],
            [['journal_id', 'year', 'number'], 'required'],
            [['journal_id', 'year', 'number'], 'integer'],
            [['status'], 'string'],
            ['status', 'in', 'range' => array_keys(self::optsStatus())],
            [['journal_id'], 'exist', 'skipOnError' => true, 'targetClass' => Journal::class, 'targetAttribute' => ['journal_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'journal_id' => 'Journal ID',
            'year' => 'Year',
            'number' => 'Number',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Journal]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJournal()
    {
        return $this->hasOne(Journal::class, ['id' => 'journal_id']);
    }


    /**
     * column status ENUM value labels
     * @return string[]
     */
    public static function optsStatus()
    {
        return [
            self::STATUS_ACTIVE => 'active',
            self::STATUS_INACTIVE => 'inactive',
        ];
    }

    /**
     * @return string
     */
    public function displayStatus()
    {
        return self::optsStatus()[$this->status];
    }

    /**
     * @return bool
     */
    public function isStatusActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function setStatusToActive()
    {
        $this->status = self::STATUS_ACTIVE;
    }

    /**
     * @return bool
     */
    public function isStatusInactive()
    {
        return $this->status === self::STATUS_INACTIVE;
    }

    public function setStatusToInactive()
    {
        $this->status = self::STATUS_INACTIVE;
    }

    public function getStatusLabel(): string
    {
        return self::statusOptions()[$this->status] ?? 'Unknown';
    }

    /**
     * column status ENUM value labels
     * @return string[]
     */
    public static function statusOptions()
    {
        return [
            self::STATUS_ACTIVE => 'active',
            self::STATUS_INACTIVE => 'inactive',
        ];
    }
}
