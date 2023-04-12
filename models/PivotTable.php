<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pivot_table".
 *
 * @property int $id_pivot
 * @property int|null $id_author
 * @property int|null $id_book
 */
class PivotTable extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pivot_table';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_author', 'id_book'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_pivot' => 'Id Pivot',
            'id_author' => 'Id Author',
            'id_book' => 'Id Book',
        ];
    }
}
