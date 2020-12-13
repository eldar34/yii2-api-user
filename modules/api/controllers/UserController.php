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

 /**
 * @OA\Info(title="Yii2-user-api", version="0.1")
 * 
 *  @OA\Schemes(format="http")
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      in="header",
 *      name="Authorization",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 * )
 * 
 *  @OA\Tag(
 *     name="Auth",
 *     description="Get Auth Token",
 * )
 * @OA\Tag(
 *     name="User",
 *     description="Users endpoints",
 * )
 *  @OA\Tag(
 *     name="Post",
 *     description="Posts endpoints",
 * )
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

/**
 * @OA\Get(path="/v1/user", tags={"User"},
 * @OA\Response(response="200", description="List user"),        
 * @OA\Response(response="405", description="Method Not Allowed"),        
 * @OA\Response(response="404", description="Not Found")        
 * )
 * 
 */

    public function actionIndex()
    {        
        return new ActiveDataProvider([
            'query' => User::find(),
        ]);
    }

/**
 * @OA\POST(path="/v1/user", tags={"User"},
 *      @OA\RequestBody(
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  required={"username", "email", "password"},
 *                  @OA\Property(property="username", type="string"),
 *                  @OA\Property(property="email", type="string"),
 *                  @OA\Property(property="password", type="string"),
 *              )
 *          )
 *      ),
 * @OA\Response(response=201, description="Successful operation")
 * )
*/

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

/**
* @OA\Get(
*     path="/v1/user/{id}",
*     summary="Get user by ID",
*     description="Returns a single user",
*     operationId="view",
*     tags={"User"},
*     @OA\Parameter(
*         description="ID of user to return",
*         in="path",
*         name="id",
*         required=true,
*         @OA\Schema(
*           type="integer",
*           format="int64"
*         )
*     ),
*     @OA\Response(
*         response=200,
*         description="Successful operation"
*     ),
*     @OA\Response(
*         response="400",
*         description="Invalid ID supplied"
*     ),
*     @OA\Response(
*         response="404",
*         description="User not found"
*     )
* )
*/

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

/**
 * @OA\PUT(path="/v1/user/{id}", tags={"User"},
 *      @OA\RequestBody(
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  required={"username", "email", "password"},
 *                  @OA\Property(property="username", type="string"),
 *                  @OA\Property(property="email", type="string"),
 *                  @OA\Property(property="password", type="string"),
 *              )
 *          )
 *      ),
 * 
 * summary="Update user by ID",
 * @OA\Parameter(
 *   description="ID of user to update",
 *   in="path",
 *   name="id",
 *   required=true,
 *   @OA\Schema(
 *     type="integer",
 *     format="int64"
 *   )
 * ),
 * @OA\Response(response=200, description="Successful operation"),
 * @OA\Response(response=422, description="Unprocessable Entity"),
 * @OA\Response(response="404",description="User not found")
 * )
  */

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
/**
* @OA\DELETE(
*     path="/v1/user/{id}",
*     summary="Delete user by ID",
*     description="Delete user",
*     operationId="view",
*     tags={"User"},
*     @OA\Parameter(
*         description="ID of user to delete",
*         in="path",
*         name="id",
*         required=true,
*         @OA\Schema(
*           type="integer",
*           format="int64"
*         )
*     ),
*     @OA\Response(
*         response=204,
*         description="NoContent"
*     ),
*     @OA\Response(
*         response="404",
*         description="User not found"
*     )
* )
*/

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
