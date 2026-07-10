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
 */
class JournalNumbers extends \yii\db\ActiveRecord
{


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
            [['journal_id', 'year', 'number'], 'required'],
            [['journal_id', 'year', 'number'], 'integer'],
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
        ];
    }

}
