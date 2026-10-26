<?php

namespace backend\modules\content\models;

use common\models\PageBlockItem;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class PageBlockItemSearch extends PageBlockItem
{
    public $title;

    public function rules(): array
    {
        return [
            [['id', 'pos'], 'integer'],
            [['title'], 'safe'],
        ];
    }

    public function scenarios(): array
    {
        return Model::scenarios();
    }

    public function search(array $params, $page_block_id = 0): ActiveDataProvider
    {
        $query = PageBlockItem::find()
            ->alias('c')
            ->where(['page_block_id' => $page_block_id])
            ->joinWith('translations t')
            ->groupBy('c.id')
            ->orderBy(['c.pos' => SORT_ASC]);

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
        ]);

        $query->andFilterWhere(['like', 't.title', $this->title]);

        return $dataProvider;
    }
}
