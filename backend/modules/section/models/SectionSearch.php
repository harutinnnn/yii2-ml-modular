<?php

namespace backend\modules\section\models;

use common\models\Section;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class SectionSearch extends Section
{
    public function rules(): array
    {
        return [
            [['id', 'position'], 'integer'],
            [['title', 'key'], 'safe'],
        ];
    }

    public function scenarios(): array
    {
        return Model::scenarios();
    }

    public function search(array $params): ActiveDataProvider
    {
        $query = Section::find()->orderBy(['position' => SORT_ASC, 'title' => SORT_ASC, 'id' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id, 'position' => $this->position])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'key', $this->key]);

        return $dataProvider;
    }
}
