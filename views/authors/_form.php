<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Authors */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="authors-form">

    <?php $form = ActiveForm::begin(['id'=> $model->formName()]); ?> 
    <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'middle_name')->textInput(['maxlength' => true]) ?>

        <?php if ( !Yii::$app->request->isAjax ): ?>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success form_button__no_ajax' , 'type'=>'submit' , 'data-pjax' => 0]) ?>
            </div>
        <?php else: ?>

        <?= Html::button(Yii::t('app', 'Close'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
            Html::button(Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'type' => "submit" , 'id' =>'submit_save_modal'])?>

        <?php endif; ?>


   
    <?php ActiveForm::end(); ?>

</div>


<?php

/// check modal form 

$this->registerJs(<<<JS
    $('form#{$model->formName()}').on('beforeSubmit', function(e) { 

        if ( !$(this).find('button').hasClass('form_button__no_ajax') ) {

            let form = $(this);
            let url = form.attr('action');


            console.log(url);

            $.ajax({
                type: "post",
                url: url,
                data: form.serialize(),
            
                success: function (result) {

                    if (result.message == 'success') {
                        $(document).find('#modalAuthor').modal('hide');
                        $.pjax.reload({container:'#table_grid_authors'});
                    } else {
                        $(form).trigger('reset');
                        $('#message').html(result.message)
                    }
    
                },
                error:function (error) {
                    console.log(error);
                    return false;
                }
            });

            return false;
           
        }
    });
JS
);
?>


