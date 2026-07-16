<?php

namespace backend\modules\journal\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\JournalSections;

/**
 * JournalSectionsSearch represents the model behind the search form of `common\models\JournalSections`.
 */
class JournalSectionsSearch extends JournalSections
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'journal_id', 'status'], 'integer'],
            [['journal_section_type'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = JournalSections::find()
            ->alias('ec')
            ->joinWith('translations ect')
            ->groupBy('ec.id');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'journal_id' => SORT_ASC,
                    'journal_section_type' => SORT_ASC,
                ],
            ],
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'journal_id' => $this->journal_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'journal_section_type', $this->journal_section_type]);

        return $dataProvider;
    }
}
