<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $books repositories\Books[] */

$this->title = 'Books';
$this->params['breadcrumbs'][] = $this->title;
//dd();
?>
    <div class="container">
        <h1 class="text-center mb-4 "><?= Html::encode($this->title) ?></h1>
        <div class="row main-b-p ">
            <?php foreach ($books->query->all() as $book): ?>
                <?php if(empty($book)) continue; ?>
                <div class="col-md-4 block-book">
                    <div class="card ">
                        <?= Html::img($book['image_path'] ?? Yii::$app->params['cap_for_image'], ['class' => 'card-img-top', 'alt' => $book['name_book']]) ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= Html::encode($book['name_book']) ?></h5>
                            <p class="card-text"><?= Html::encode($book['short_description']) ?></p>
                            <div class="card-footer">
                                <small class="text-muted"><?= Yii::$app->formatter->asDate($book['publication_date'], 'php:d M Y') ?></small>
                                <div class="authors">
                                    <strong>Authors:</strong>
                                    <?php foreach (explode(',',$book['authors'] ?? '') as $author): ?>
                                        <span class="badge badge-secondary"><?= Html::encode($author) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?= Html::a('View', ['view', 'id' => $book['id']], ['class' => 'btn btn-primary mt-3']) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

<?php
/*
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/doc/">Yii Documentation &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
*/
?>