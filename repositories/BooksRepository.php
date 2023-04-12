<?php


namespace repositories;

use app\models\Authors;
use app\models\Books as Model;
use app\models\PivotTable;
use app\models\search\Books as SearchModel;


class BooksRepository extends CoreRepository
{
    public $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = $this->startCondition();
    }

    public function getModel():Model
    {
        return $this->startCondition();
    }

    protected function getModelClass():string
    {
       return Model::class;
    }

    protected function getModeSearchClass():string
    {
        return SearchModel::class;
    }

    public function getsAuthors(int $id = 0)
    {
        $array_all_authors = function(array $data){ // array compress authors
            $array = [];
                foreach ($data as $key ) {
                    $array[$key['id']]  = $key['full_name'];
                }
            return $array;
        };

        $res = [
            'all_authors'=> $array_all_authors((new AuthorsRepository())->getAllAuthorsForDropDownList()),
            'select_authors'=> (new PivotRepository())->getAuthorsByBookIdForDropDownList($id),
        ];
        return  $res;
    }


    public function getAllBooks()
    {
       $res = $this->startCondition()::find()
           ->select([
               "GROUP_CONCAT(CONCAT(a.surname,' ', a.middle_name,' ',a.name) SEPARATOR ', ') AS authors",
               'books.id',
               'books.name_book',
               'books.short_description',
               'books.image_path',
               'DATE_FORMAT(FROM_UNIXTIME(publication_date), "%Y-%m-%d") AS publication_date'
           ])
           ->innerJoin(['pt' => PivotTable::tableName()], 'books.id = pt.id_book')
           ->leftJoin(['a' => Authors::tableName()], 'pt.id_author = a.id')
           ->groupBy('books.id')
           ->asArray()
           ->all();

       return $res;
    }







}