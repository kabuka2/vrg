<?php


namespace repositories;
use app\models\Authors;
use app\models\Authors as Model;
use app\models\Books;
use app\models\PivotTable;
use app\models\search\Authors as SearchModel;
//use repositories\AuthorsRepository as Authors;

class AuthorsRepository extends CoreRepository
{

    protected function getModelClass():string
    {
        return Model::class;
    }

    protected function getModeSearchClass():string
    {
        return SearchModel::class;
    }

    public function getAllAuthorsForDropDownList():array
    {
        $data =  $this->startCondition()::find()
            ->select(['id','CONCAT(surname," ",name," ",middle_name) as full_name'])
            ->asArray()
            ->all();
       return $data;
    }








}