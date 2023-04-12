<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\Authors */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Authors');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authors-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
    
    
        <p>
            <?= Html::button(Yii::t('app', 'Create Authors') , ['value'=> Url::toRoute(['create']) , 'class' => 'btn btn-success ajax_button' ]); ?>
        </p>

        <?php Pjax::begin(); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'id'=>'table_grid_authors',
                
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id',
                    'surname',
                    'name',
                    'middle_name',

                    [
                        'class' => yii\grid\ActionColumn::class, 
                        'template' => '{update} {delete}',
                        'buttons' => [
                            'update'=> function ($url, $model, $key) {
                                $options = [
                                    'title' => Yii::t('app' , 'Edit'),
                                    'aria-label' => Yii::t('app' , 'Edit'),
                                    'data-pjax' => '0',
                                    'class'=>'ajax_button',
                                    'value'=>$url,
                                ];
                                $url = '#';
                                $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-pencil"]);
                                return Html::a($icon, $url, $options );
                                
                            }
                        ],
                    ],
                ],
            ]); ?>
         <?php Pjax::end(); ?>
        
    <?php 
    
        Modal::begin([
            'header' => $this->title,
            'id'=>'modalAuthor',
            'size'=>'modal-lg',
            'toggleButton' => false ,
           
        ]);

        echo "<div id='modalContent'></div>";

        Modal::end();
      
    ?>


</div>


