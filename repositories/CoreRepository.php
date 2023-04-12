<?php
namespace repositories;

use yii\base\Model;

abstract class CoreRepository
{

    protected $model;
    protected $search_model;

    public function __construct()
    {
        $this->model = $this->getModelClass();
        $this->search_model = $this->getModeSearchClass();
    }

    abstract protected function getModelClass():string;

    abstract protected function getModeSearchClass():string;

    protected function startCondition()
    {
        return clone new $this->model;
    }

    protected function startConditionSearch()
    {
        return clone new $this->model();
    }

}