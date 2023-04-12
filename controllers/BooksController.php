<?php

namespace app\controllers;

use repositories\AuthorsRepository;
use Yii;

use repositories\BooksRepository;
use app\models\search\Books as BooksSearch;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;
use app\models\Books;

/**
 * BooksController implements the CRUD actions for Books model.
 */
class BooksController extends CoreController
{

    private $_repository;
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->_repository = (new BooksRepository());
    }

    /**
     * Lists all Books models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BooksSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'all_authors' => (new AuthorsRepository())->getAllAuthorsForDropDownList(),
        ]);
    }


    /**
     * Creates a new Books model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if ($this->request->isAjax && empty($this->request->post())) {
            return $this->renderAjax(
                'create' ,
                [
                    'model'=> $this->_repository->getModel(),
                    'author' => $this->_repository->getsAuthors(),
                    'form_action' => Url::toRoute('books/addition'),
                ]
            );
        }

        throw new \yii\web\NotFoundHttpException();

    }

    public function actionAddition()
    {
        if (!Yii::$app->request->isAjax ) {
            throw new \yii\web\NotFoundHttpException('Page not found!');
        }

        $model = $this->_repository->getModel();

        if (!$model->load( Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        $model->image_temp = UploadedFile::getInstance($model, 'image');
        $model->uploadPhoto();
        if ( !$model->save() ) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        $model->authorsCrudBooks();

        return Yii::createObject([
            'class' => 'yii\web\Response',
            'format' => \yii\web\Response::FORMAT_JSON,
            'data' => [
                'message' => 'success',
                'code' => 200,
            ],
        ]);
    }



    /**
     * Updates an existing Books model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel((int)$id);

        if ( empty($model) || !Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException();
        }

        if ( $model->load(Yii::$app->request->post())) {

            $model->image_temp = UploadedFile::getInstance($model, 'image');
            $result = $model->checkStatusImage();

            $model->authorsCrudBooks();

            if ( !$model->save() ) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            return Yii::createObject([
                'class' => 'yii\web\Response',
                'format' => \yii\web\Response::FORMAT_JSON,
                'data' => [
                    'message' => 'success',
                    'code' => 200,
                ],
            ]);

        } else {
            $author = $this->_repository->getsAuthors($id);
            return $this->renderAjax(
                'update',
                [
                    'model'=>$model ,
                    'author'=>$author,
                    'form_action' => Url::toRoute(['books/update','id' => $id]),
                ]
            );
        }
    }

    /**
     * Deletes an existing Books model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        $model->deleteImage();
        $model->deletePivot();
        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Books model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Books the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ($this->_repository->getModel())::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
