<?php

namespace backend\modules\posts\models;

use common\models\Post;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class PostSearch extends Post
{
    public $title;

    public function rules(): array
    {
        return [
            [['id', 'status'], 'integer'],
            [['title'], 'safe'],
        ];
    }

    public function scenarios(): array
    {
        return Model::scenarios();
    }

    public function search(array $params): ActiveDataProvider
    {
        $query = Post::find()
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
        ]);

        $query->andFilterWhere(['like', 't.title', $this->title]);

        return $dataProvider;
    }
}
