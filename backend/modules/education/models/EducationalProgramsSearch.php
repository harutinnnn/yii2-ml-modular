<?php

namespace backend\modules\education\models;

use common\models\EducationalPrograms;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class EducationalProgramsSearch extends EducationalPrograms
{
    public $title;

    public function rules(): array
    {
        return [
            [['id', 'status','education_level'], 'integer'],
            [['title'], 'safe'],
        ];
    }

    public function scenarios(): array
    {
        return Model::scenarios();
    }

    public function search(array $params): ActiveDataProvider
    {
        $query = EducationalPrograms::find()
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
