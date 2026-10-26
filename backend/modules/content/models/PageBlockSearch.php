<?php

namespace backend\modules\content\models;

use common\models\PageBlock;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class PageBlockSearch extends PageBlock
{
    public $title;
    public $pre_title;

    public function rules(): array
    {
        return [
            [['id', 'status'], 'integer'],
            [['title','pre_title'], 'safe'],
        ];
    }

    public function scenarios(): array
    {
        return Model::scenarios();
    }

    public function search(array $params): ActiveDataProvider
    {
        $query = PageBlock::find()
            ->alias('c')
            ->joinWith('translations t')
            ->groupBy('c.id')
            ->orderBy(['c.id' => SORT_DESC]);

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
            'c.id' => $this->id,
            'c.status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 't.title', $this->title]);

        return $dataProvider;
    }
}
