<?php


namespace repositories;
use app\models\PivotTable;
use app\models\PivotTable as Model;


class PivotRepository extends CoreRepository
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function getModelClass(): string
    {
        return Model::class;
    }

    protected function getModeSearchClass(): string
    {
       return '';
    }

    /**
     * @param int $book_id *
     * @return array
     */
    public function getAuthorsByBookIdForDropDownList(int $book_id):array
    {
        /**@var app\models\PivotTable **/
       $res = $this->startCondition()::find()


       ->select(['id_author as select_authors_book'])
//       ->leftJoin(
//           [
//             'a' => (new AuthorsRepository)->startCondition()::tableName()
//           ],
//          'pivot_table.id_author = a.id'
//       )
       ->where(['[[pivot_table.id_book]]' => $book_id])
       ->asArray()
       ->all();
       return $res;
    }
}