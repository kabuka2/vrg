<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Books as BooksModel;

/**
 * Books represents the model behind the search form of `app\models\Books`.
 */
class Books extends BooksModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['publication_date'], 'date', 'format' => 'dd.MM.yyyy'],
            [['name_book', 'short_description','authors'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
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
        $query = BooksModel::find();
        $query->joinWith('authors');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
               'pageSize' => 15,
           ],
            'sort' => [
                'attributes' => [
                    'id' => [],
                    'customer_id',
                    'total_amount',
                    'id' => [
                        'asc' => [
                            'books.id' => SORT_ASC,
                            'books.id' => SORT_ASC,
                            'books.id' => SORT_ASC
                        ],
                        'desc' => [
                            'books.id' => SORT_DESC,
                            'books.id' => SORT_DESC,
                            'books.id' => SORT_DESC
                        ],
                    ],

                    'name_book' => [
                        'asc' => [
                            'books.name_book' => SORT_ASC,
                            'books.name_book' => SORT_ASC,
                            'books.name_book' => SORT_ASC
                        ],
                        'desc' => [
                            'books.name_book' => SORT_DESC,
                            'books.name_book' => SORT_DESC,
                            'books.name_book' => SORT_DESC
                        ],
                    ],

                    'short_description' => [
                        'asc' => [
                            'books.short_description' => SORT_ASC,
                            'books.short_description' => SORT_ASC,
                            'books.short_description' => SORT_ASC
                        ],
                        'desc' => [
                            'books.short_description' => SORT_DESC,
                            'books.short_description' => SORT_DESC,
                            'books.short_description' => SORT_DESC
                        ],
                    ],

                    'publication_date' => [
                        'asc' => [
                            'books.publication_date' => SORT_ASC,
                            'books.publication_date' => SORT_ASC,
                            'books.publication_date' => SORT_ASC
                        ],
                        'desc' => [
                            'books.publication_date' => SORT_DESC,
                            'books.publication_date' => SORT_DESC,
                            'books.publication_date' => SORT_DESC
                        ],
                    ],

                    'authors' => [
                        'asc' => [
                            'authors.surname' => SORT_ASC,
                            'authors.name' => SORT_ASC,
                            'authors.middle_name' => SORT_ASC
                        ],
                        'desc' => [
                            'authors.surname' => SORT_DESC,
                            'authors.name' => SORT_DESC,
                            'authors.middle_name' => SORT_DESC
                        ],
                    ],

                ],
            ]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);
        $query->select([
            'books.id',
            'name_book',
            'image_path',
            'publication_date',
            'short_description',
            "GROUP_CONCAT(CONCAT(`authors`.`surname`, ' ', `authors`.`middle_name`, ' ', `authors`.`name`) SEPARATOR ', ') AS `authors`"
            ]);
        $query->andFilterWhere(['like', 'name_book', $this->name_book])
            ->andFilterWhere(['like', 'short_description', $this->short_description])
            ->andFilterWhere(['like', 'publication_date',  !empty($this->publication_date) ? strtotime($this->publication_date): '']);
            if (!empty($this->authors)) {
                $query->innerJoinWith('authors', false)
                    ->andWhere(['IN', 'authors.id', $this->authors]);
                  //  ->andFilterWhere(['IN', 'authors.id', $this->authors]);
            }
            $query->groupBy('books.id');
        return $dataProvider;
    }
}
