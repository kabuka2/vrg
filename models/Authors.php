<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "authors".
 *
 * @property int $id
 * @property string $surname
 * @property string $name
 * @property string|null $middle_name
 */
class Authors extends \yii\db\ActiveRecord
{
    public $created;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'authors';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['surname', 'name'], 'required'],
            [['name', 'middle_name'], 'string','max' => 50],
            [['created'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['surname'],'string' ,'length'=> [3,50]],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'surname' => Yii::t('app', 'Surname'),
            'name' => Yii::t('app', 'Name'),
            'middle_name' => Yii::t('app', 'Middle Name'),
        ];
    }
}
