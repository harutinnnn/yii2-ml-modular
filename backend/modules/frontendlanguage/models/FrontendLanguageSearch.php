<?php

namespace backend\modules\frontendlanguage\models;

use common\models\FrontendLanguage;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class FrontendLanguageSearch extends FrontendLanguage
{
    public function rules(): array
    {
        return [
            [['id', 'status'], 'integer'],
            [['type', 'key'], 'safe'],
        ];
    }

    public function scenarios(): array
    {
        return Model::scenarios();
    }

    public function search(array $params): ActiveDataProvider
    {
        $query = FrontendLanguage::find()
            ->alias('fl')
            ->joinWith('translations flm')
            ->groupBy('fl.id')
            ->orderBy(['fl.id' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'fl.id' => $this->id,
            'fl.status' => $this->status,
        ]);

        $query
            ->andFilterWhere(['fl.type' => $this->type])
            ->andFilterWhere(['like', 'fl.key', $this->key]);

        return $dataProvider;
    }
}
