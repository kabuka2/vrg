<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Books */
/* @var $author array*/
/* @var $form_action string*/

$this->title = Yii::t('app', 'Update Books: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Books'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="books-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'author'=>$author,
        'form_action' => $form_action,
    ]) ?>

</div>
