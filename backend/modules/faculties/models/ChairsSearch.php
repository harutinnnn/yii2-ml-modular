<?php

namespace backend\modules\faculties\models;

use common\models\Chairs;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class ChairsSearch extends Chairs
{
    public $title;

    public function rules(): array
    {
        return [
            [['id', 'status'], 'integer'],
            [['title', 'title'], 'safe'],
        ];
    }

    public function scenarios(): array
    {
        return Model::scenarios();
    }

    public function search(array $params): ActiveDataProvider
    {
        $query = Chairs::find()
            ->alias('ec')
            ->joinWith('translations ect')
            ->groupBy('ec.id')
            ->orderBy(['ec.faculty_id' => SORT_ASC, 'ec.pos' => SORT_ASC]);

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
            'ec.id' => $this->id,
            'ec.status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'ect.title', $this->title]);

        return $dataProvider;
    }
}
