<?php

namespace backend\modules\settings\models;

use common\models\Setting;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class SettingSearch extends Setting
{
    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['key', 'title', 'value'], 'safe'],
        ];
    }

    public function scenarios(): array
    {
        return Model::scenarios();
    }

    public function search(array $params): ActiveDataProvider
    {
        $query = Setting::find()->orderBy(['title' => SORT_ASC, 'id' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['like', 'key', $this->key])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'value', $this->value]);

        return $dataProvider;
    }
}
