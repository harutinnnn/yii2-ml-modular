<?php

namespace backend\modules\emailcontent\models;

use common\models\EmailContent;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class EmailContentSearch extends EmailContent
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
        $query = EmailContent::find()
            ->alias('ec')
            ->joinWith('translations ect')
            ->groupBy('ec.id')
            ->orderBy(['ec.id' => SORT_DESC]);

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
