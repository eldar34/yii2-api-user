<?php

namespace app\modules\api\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\data\ActiveDataProvider;
use app\models\User;
use app\models\SignupForm;
use yii\web\NotFoundHttpException;

/**
 * User controller for the `api` module
 */
class UserController extends Controller
{
    public $modelClass = 'app\models\User';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
        // $behaviors['verbFilter']['actions'] = [
        //     'index'  => ['get'],
        // ];

        return $behaviors;
    }

    public function actionIndex()
    {        
        return new ActiveDataProvider([
            'query' => User::find(),
        ]);
    }

    public function actionCreate()
    {        
            $model = new SignupForm();
            
            // IF form load and user save
            if( $model->load(Yii::$app->getRequest()->getBodyParams(), '') ){

                $new_user = $model->signup();

                return is_null($new_user) ? $model->getErrors() : $new_user;
                
            }else{
                return $model->getErrors();
            }
            
    }

    public function actionView($id)
    {
        
        if(User::find()->where(['status'=>10, 'id'=>$id])->exists()){
            return new ActiveDataProvider([
                'query' => User::find()->select(['id', 'username', 'email'])->where(['status'=>10, 'id'=>$id]),
            ]);
        }else{
            throw new NotFoundHttpException('В БД нет такого id с статусом 10', 404);
        }
        
        
    }

}
