<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "journal_admin_lcp".
 *
 * @property int $journal_id
 * @property int $admin_id
 *
 * @property User $admin
 * @property Journal $journal
 */
class JournalAdminLcp extends \yii\db\ActiveRecord
{


    public $adminIds = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'journal_admin_lcp';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['adminIds'], 'safe'],
            [['journal_id', 'admin_id'], 'required'],
            [['journal_id', 'admin_id'], 'integer'],
            [['journal_id', 'admin_id'], 'unique', 'targetAttribute' => ['journal_id', 'admin_id']],
            [['journal_id'], 'exist', 'skipOnError' => true, 'targetClass' => Journal::class, 'targetAttribute' => ['journal_id' => 'id']],
            [['admin_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['admin_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'journal_id' => 'Journal ID',
            'admin_id' => 'Admin ID',
        ];
    }

    /**
     * Gets query for [[Admin]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAdmin()
    {
        return $this->hasOne(User::class, ['id' => 'admin_id']);
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

}
