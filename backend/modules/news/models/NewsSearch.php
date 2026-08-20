<?php

namespace backend\modules\news\models;

use common\models\News;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class NewsSearch extends News
{
    public $title;

    public function rules(): array
    {
        return [
            [['id', 'status','category_id'], 'integer'],
            [['title'], 'safe'],
        ];
    }

    public function scenarios(): array
    {
        return Model::scenarios();
    }

    public function search(array $params): ActiveDataProvider
    {
        $query = News::find()
            ->alias('p')
            ->joinWith('translations t')
            ->groupBy('p.id')
            ->orderBy(['p.id' => SORT_DESC]);

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
            'p.id' => $this->id,
            'p.status' => $this->status,
            'p.category_id' => $this->category_id,
        ]);

        $query->andFilterWhere(['like', 't.title', $this->title]);

        return $dataProvider;
    }
}
