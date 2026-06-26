<?php

namespace backend\modules\user\models;

use common\components\UserRoles;
use common\models\Student;
use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * UserSearch represents the model behind the search form of `common\models\User`.
 */
class StudentSearch extends Student
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['email', 'first_name', 'last_name', 'phone'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = User::find()
            ->innerJoin('auth_assignment aa', 'aa.user_id = user.id')->where(['aa.item_name' => UserRoles::STUDENT])
            ->joinWith('additional add');
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere([
                'like',
                'add.first_name',
                $this->first_name
            ])->andFilterWhere([
                'like',
                'add.last_name',
                $this->last_name
            ])->andFilterWhere([
                'like',
                'add.phone',
                $this->phone
            ]);

        return $dataProvider;
    }
}
