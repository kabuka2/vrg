<?php

namespace app\controllers;

use Yii;
use app\models\Authors;
use app\models\search\Authors as AuthorsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;

use yii\helpers\Html;

/**
 * AuthorsController implements the CRUD actions for Authors model.
 */
class AuthorsController extends Controller
{
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

    /**
     * Lists all Authors models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuthorsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

   
    /**
     * Creates a new Authors model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {

        $model = new Authors();
       
        if (Yii::$app->request->isAjax) {
        
            if ( $model->load(Yii::$app->request->post()) ) {
                    $model->created = date('Y-m-d H:i:s');
                if ( $model->save() ) {

                    return Yii::createObject([
                        'class' => 'yii\web\Response',
                        'format' => \yii\web\Response::FORMAT_JSON,
                        'data' => [
                            'message' => 'success',
                            'code' => 200,
                        ],
                    ]);
                } else {
                    Yii::$app->response->format = Response::FORMAT_JSON; 
                    return ActiveForm::validate($model); 
                }
            } else {
                return $this->renderAjax('create', ['model'=>$model]);
            }
            
        } else {

            if ( $model->load(Yii::$app->request->post()) ) {

              return $this->redirect(['update']);

            }
            return $this->render('create' , ['model'=>$model] );
        }

    
    }

    /**
     * Updates an existing Authors model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
         
        $model = $this->findModel((int)$id);

        if (!empty($model)) {

            if (Yii::$app->request->isAjax) { 
                
                if ( $model->load(Yii::$app->request->post()) ){

                    if ( $model->save() ) {

                        return Yii::createObject([
                            'class' => 'yii\web\Response',
                            'format' => \yii\web\Response::FORMAT_JSON,
                            'data' => [
                                'message' => 'success',
                                'code' => 200,
                            ],
                        ]);
                                            
                    } else {
                        Yii::$app->response->format = Response::FORMAT_JSON; 
                        return ActiveForm::validate($model); 
                    }
                    
                } else {
                    return $this->renderAjax('update',['model' => $model]);
                }
                
            } else {
                return $this->redirect(['index']);
            }
        }
      

        
       

       
    }

    /**
     * Deletes an existing Authors model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Authors model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Authors the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Authors::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
