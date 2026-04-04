<?php

namespace backend\modules\language\models;

use common\models\Language;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class LanguageSearch extends Language
{
    public function rules(): array
    {
        return [
            [['id', 'is_default', 'is_active', 'sort_order'], 'integer'],
            [['code', 'name'], 'safe'],
        ];
    }

    public function scenarios(): array
    {
        return Model::scenarios();
    }

    public function search(array $params): ActiveDataProvider
    {
        $query = Language::find()->orderBy(['sort_order' => SORT_ASC, 'name' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'is_default' => $this->is_default,
            'is_active' => $this->is_active,
        ]);

        $query
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
