<?php

namespace backend\forms\blog;

use shop\entities\Blog\Category;
use shop\helpers\PostHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use shop\entities\Blog\Post;
use yii\helpers\ArrayHelper;

/**
 * PostSearch represents the model behind the search form of `shop\entities\Blog\Post`.
 */
class PostSearch extends Model
{
    public $id;
    public $title;
    public $status;
    public $category_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'status'], 'integer'],
            [['title'], 'safe'],
        ];
    }


    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Post::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'category_id' => $this->category_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }

    public function categoriesList(): array
    {
        return ArrayHelper::map(Category::find()->orderBy('sort')->asArray()->all(), 'id', 'title');
    }

    public function statusList(): array
    {
            return PostHelper::statusList();
    }
}
