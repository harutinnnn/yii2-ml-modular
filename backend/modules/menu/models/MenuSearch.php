<?php

namespace backend\modules\menu\models;

use common\models\Menu;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class MenuSearch extends Menu
{
    public $title;

    public function rules(): array
    {
        return [
            [['id', 'status', 'show_in_menu', 'position', 'content_id'], 'integer'],
            [['title', 'url'], 'safe'],
        ];
    }

    public function scenarios(): array
    {
        return Model::scenarios();
    }

    public function search(array $params): ActiveDataProvider
    {
        $query = Menu::find()
            ->alias('m')
            ->joinWith('translations mt')
            ->groupBy('m.id')
            ->orderBy(['m.position' => SORT_ASC, 'm.id' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'm.id' => $this->id,
            'm.status' => $this->status,
            'm.show_in_menu' => $this->show_in_menu,
            'm.content_id' => $this->content_id,
        ]);
        $query->andFilterWhere(['like', 'mt.title', $this->title]);
        $query->andFilterWhere(['like', 'm.url', $this->url]);

        return $dataProvider;
    }
}
