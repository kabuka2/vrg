<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\Pjax;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\Books */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var array $all_authors all authors */

$this->title = Yii::t('app', 'Books');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="books-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::button(Yii::t('app', 'Create Books') , ['value'=> Url::toRoute(['create']) , 'class' => 'btn btn-success ajax_button' ]); ?>

    </p>

    <?php Pjax::begin(['id'=>'table_grid_books',]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'name_book',
                'short_description',
                [

                    'attribute' => 'authors',
                    'label' => 'Authors',
                    'value' => function($data) {
                        return $data->authors;
                    },
                    'filter' => Select2::widget([
                        'model' => $searchModel,
                        'attribute' => 'authors',
                        'data' => ArrayHelper::map($all_authors, 'id', 'full_name'),
                        'options' => ['placeholder' => 'Select authors...', 'multiple' => true],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]),
                ],
                [
                    'label' => Yii::t('app' , 'Image'),
                    'format' => 'raw',

                    'value' => function($data){

                        $img = !empty($data->image_path) ? $data->image_path : Yii::$app->params['cap_for_image'];

                         return Html::img(( $img ),[
                            'alt'=> Html::encode($data->name_book),
                            'style' => 'width:100px;'
                        ]);
                    },
                ],
                [
                    'attribute'=>'publication_date',
                    'label'=> Yii::t('app','Publication Date'),
                    'format'=>['date', 'dd.MM.Y'],
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete}',
                    'buttons' => [
                        'update'=> function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('app' , 'Edit'),
                            'aria-label' => Yii::t('app' , 'Edit'),
                            'class'=>'ajax_button',
                            'data-pjax'=>0,
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
        'class'=>'modal hide fade',
        'toggleButton' => false ,
        
    ]);

    echo "<div id='modalContent'></div>";
    Modal::end();
  
    ?>





</div>



