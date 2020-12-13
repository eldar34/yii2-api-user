<?php

namespace app\modules\api\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\Response;
use yii\data\ActiveDataProvider;
use app\models\User;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * User controller for the `api` module
 */
class UserController extends ActiveController
{
    public $modelClass = 'app\models\User';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();

        // отключить действия "delete" и "create"
        unset($actions['create'], $actions['index'], $actions['view'], $actions['update'], $actions['delete']);

        return $actions;
    }

    public function actionIndex()
    {        
        return new ActiveDataProvider([
            'query' => User::find(),
        ]);
    }

    public function actionCreate()
    {        
            $model = new User(['scenario' => User::SCENARIO_USER_SIGNUP]);
            
            // IF form load and user save
            if( $model->load(Yii::$app->getRequest()->getBodyParams(), '') && $model->save()){

                Yii::$app->response->statusCode = 201;
                return $model;
                
            }else{
                Yii::$app->response->statusCode = 422;
                return $model->getErrors();
            }
            
    }

    public function actionView($id)
    {
        
        if(User::find()->where(['status'=>USER::STATUS_ACTIVE, 'id'=>$id])->exists()){
            return new ActiveDataProvider([
                'query' => User::find()->select(['id', 'username', 'email'])->where(['status'=>USER::STATUS_ACTIVE, 'id'=>$id]),
            ]);
        }else{
            throw new NotFoundHttpException('В БД нет такого id с статусом 10', 404);
        }
        
        
    }

    public function actionUpdate($id)
    { 
       
        $user = $this->findModel($id);
        $user->scenario = User::SCENARIO_USER_UPDATE;

        if( $user->load(Yii::$app->getRequest()->getBodyParams(), '') && $user->save()){

            return $user;
        }else{
            Yii::$app->response->statusCode = 422;
            return $user->getErrors();
        }
    }

    public function actionDelete($id)
    {       
        $model = $this->findModel($id);
        $model->status = USER::STATUS_DELETED;
        if($model->update()){
            Yii::$app->response->statusCode = 204;
        }else{
            throw new NotFoundHttpException('В БД нет такого id с статусом 10', 404);
        }
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('В БД нет такого id', 404);
    }

}
