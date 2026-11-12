<?php

namespace backend\modules\library\models;

use common\models\LibraryItem;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class LibraryItemSearch extends LibraryItem
{
    public $title;

    public function rules(): array
    {
        return [
            [['id', 'status'], 'integer'],
            [['title','type','library_module_id'], 'safe'],
        ];
    }

    public function scenarios(): array
    {
        return Model::scenarios();
    }

    public function search(array $params): ActiveDataProvider
    {
        $query = LibraryItem::find()
            ->alias('p')
            ->joinWith('translations t')
            ->groupBy('p.id');

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
        $query->andFilterWhere(['like', 'type', $this->type]);
        $query->andFilterWhere(['like', 'library_module_id', $this->library_module_id]);

        return $dataProvider;
    }
}
