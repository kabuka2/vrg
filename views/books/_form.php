<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\Url;
use unclead\multipleinput\MultipleInput;

/**@var app\models\Books $model **/
/**@var array $author  **/
/**@var string $form_action  **/
?>

<div class="books-form">

        <?php $form = ActiveForm::begin([
                'options' => [
                        'enctype' => 'multipart/form-data',
                    'id'=> $model->formName()
                ],
                'action' => $form_action,
        ]); ?>

        <?= $form->field($model, 'name_book')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'short_description')->textarea(['maxlength' => true, 'class'=> 'form-control']) ?>
        <?= $form->field($model, 'image')->fileInput(['id'=>'input_photo' ,'value' =>  !empty($model->image_path) ? Html::encode($model->image_path) : '', 'class'=>'input-group-text' , 'style' => !empty($model->image_path) ? 'display:none' : 'display:block']) ?>

        <?php if ( !empty($model->image_path) ):  ?>

            <div class="images_block">
                <span class = "icon_delete_photo"><i class="glyphicon glyphicon-trash"></i></span>
                <div class ="block_photo_modal">
                    <?= Html::img(Url::to(Html::encode($model->image_path)) , ['width' => '100%' , 'class'=>'img-thumbnail']) ?>
                </div>
            </div>

        <?php endif; ?>


        <?= $form->field($model ,'hidden_image_status')->hiddenInput(['value'=> !empty($model->image_path) ? 1 : 0 ])->label(false)?>
        <?= $form->field($model, 'select_authors_book[]')->widget(MultipleInput::class, [
            'max' => Html::encode(count($author['all_authors'])),
            'id' => 'select_authors_book',
            'columns' => [
                [
                    'name' => 'select_authors_book',
                    'type' => 'dropDownList',
                    'items' => $author['all_authors'],
                    'options' => [
                        'prompt' => Html::encode(Yii::t('app', 'Select author')),
                    ],
                ],
            ],
            'data' => $author['select_authors'],
        ])->label(Yii::t('app','Authors'))
          ->hint(false)
          ->error(false);
        ?>


        <?= $form->field($model, 'publication_date')->widget(DatePicker::className(),
            [
            'name'=>'date',
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'options' => [
                'placeholder' => Yii::t('app' , 'Select publication date'),
                'value' => !empty($model->publication_date) ? date('d.m.Y',$model->publication_date) : '',
            ],
            'pluginOptions' => [
                
                'autoclose' => true,
                'endDate'=> date('d.m.Y'),
                'format' => 'dd.mm.yyyy',
                'todayHighlight' => true,

            ]
        ]) ?>
    

        <?php if ( !Yii::$app->request->isAjax ): ?>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success form_button__no_ajax' ]) ?>
            </div>
        <?php else: ?>

        <?= Html::button(Yii::t('app', 'Close'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
            Html::button(Yii::t('app', 'Save'), ['class' => 'btn btn-primary' ,'type'=>'submit' , 'id' =>'submit_save_modal'])?>

        <?php endif; ?>

    <?php ActiveForm::end(); ?>

</div>


