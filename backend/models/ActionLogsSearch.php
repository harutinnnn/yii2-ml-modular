<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ActionLogs;

/**
 * ActionLogsSearch represents the model behind the search form of `common\models\ActionLogs`.
 */
class ActionLogsSearch extends ActionLogs
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'entity_id'], 'integer'],
            [['action', 'entity_type', 'description', 'old_values', 'new_values', 'ip_address', 'user_agent', 'request_method', 'request_url', 'created_at'], 'safe'],
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
        $query = ActionLogs::find()->joinWith(['user'])->orderBy(['created_at' => SORT_DESC]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'user_id' => $this->user_id,
            'entity_id' => $this->entity_id,
        ]);

        if ($this->created_at) {
            $from = $this->created_at . ' 00:00:00';
            $to = date('Y-m-d H:i:s', strtotime($this->created_at . ' +1 day'));

            $query
                ->andWhere(['>=', 'action_logs.created_at', $from])
                ->andWhere(['<', 'action_logs.created_at', $to]);
        }

        $query->andFilterWhere(['like', 'action', $this->action])
            ->andFilterWhere(['like', 'entity_type', $this->entity_type])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'old_values', $this->old_values])
            ->andFilterWhere(['like', 'new_values', $this->new_values])
            ->andFilterWhere(['like', 'ip_address', $this->ip_address])
            ->andFilterWhere(['like', 'user_agent', $this->user_agent])
            ->andFilterWhere(['like', 'request_method', $this->request_method])
            ->andFilterWhere(['like', 'request_url', $this->request_url]);

        return $dataProvider;
    }
}
