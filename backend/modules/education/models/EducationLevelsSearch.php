<?php

namespace backend\modules\education\models;

use common\models\EducationLevels;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class EducationLevelsSearch extends EducationLevels
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
        $query = EducationLevels::find()
            ->alias('p')
            ->joinWith('translations t')
            ->groupBy('p.id')
            ->orderBy(['p.pos' => SORT_ASC]);

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
