<?php

namespace backend\modules\journal\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\JournalNumbers;

/**
 * JournalNumbersSearch represents the model behind the search form of `common\models\JournalNumbers`.
 */
class JournalNumbersSearch extends JournalNumbers
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'journal_id', 'year', 'number'], 'integer'],
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
    public function search($params, $journal_id)
    {
        $query = JournalNumbers::find()->where(['journal_id' => $journal_id]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, null);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'journal_id' => $this->journal_id,
            'year' => $this->year,
            'number' => $this->number,
        ]);

        return $dataProvider;
    }
}
