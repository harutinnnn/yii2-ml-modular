<?php

namespace backend\modules\education\models;

use common\models\EducationPlan;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class EducationPlanSearch extends EducationPlan
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

    public function search(array $params,int $planId): ActiveDataProvider
    {
        $query = EducationPlan::find()
            ->alias('p')
            ->joinWith('translations t')
            ->where(['p.educational_program_id' => $planId])
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
