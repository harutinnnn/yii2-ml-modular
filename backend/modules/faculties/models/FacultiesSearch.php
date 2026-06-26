<?php

namespace backend\modules\faculties\models;

use common\models\Faculties;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class FacultiesSearch extends Faculties
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
        $query = Faculties::find()
            ->alias('ec')
            ->joinWith('translations ect')
            ->groupBy('ec.id')
            ->orderBy(['ec.pos' => SORT_ASC]);

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
