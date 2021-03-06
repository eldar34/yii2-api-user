<?php

namespace app\modules\api\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\web\Response;
use app\models\Post;

/**
 * Post controller for the `api` module
 */

class PostController extends ActiveController
{

/**
 * 
 * @OA\Get(
 *      path="/v1/posts",
 *      tags={"Post"},
 *      description="Get list of posts",
 *      security={{"bearerAuth":{}}}, 
 * 
 *       @OA\Response(response="200", description="Posts list"),        
 *       @OA\Response(response=401, description="Unauthorized"),
 *       @OA\Response(response=404, description="Not Found"),
 * )
 * 
 * @OA\Get(
 *     path="/v1/posts/{id}",
 *     summary="Get post by ID",
 *     description="Returns a single post",
 *     operationId="viewPostById",
 *     tags={"Post"},
 *     @OA\Parameter(
 *         description="ID of post to return",
 *         in="path",
 *         name="id",
 *         required=true,
 *         @OA\Schema(
 *           type="integer",
 *           format="int64"
 *         )
 *     ),
 *      security={{"bearerAuth":{}}}, 
 * 
 *       @OA\Response(response="200", description="Posts list"),        
 *       @OA\Response(response=401, description="Unauthorized"),
 *       @OA\Response(response=404, description="Not Found"),
 * )
 * 
 * @OA\POST(path="/v1/posts", tags={"Post"},
 *      @OA\RequestBody(
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  required={"title", "content"},
 *                  @OA\Property(property="title", type="string"),
 *                  @OA\Property(property="content", type="string")
 *              )
 *          )
 *      ),
 * 
 * security={{"bearerAuth":{}}},
 * @OA\Response(response=201, description="Successful operation"),
 * @OA\Response(response=422, description="Unprocessable Entity")
 * )
 * 
 * @OA\PUT(path="/v1/posts/{id}", tags={"Post"},
 *      @OA\RequestBody(
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  required={"title", "content"},
 *                  @OA\Property(property="title", type="string"),
 *                  @OA\Property(property="content", type="string")
 *              )
 *          )
 *      ),
 * 
 * summary="Update post by ID",
 * @OA\Parameter(
 *   description="ID of post to update",
 *   in="path",
 *   name="id",
 *   required=true,
 *   @OA\Schema(
 *     type="integer",
 *     format="int64"
 *   )
 * ),
 * security={{"bearerAuth":{}}}, 
 * 
 * @OA\Response(response=200, description="Successful operation"),
 * @OA\Response(response=422, description="Unprocessable Entity"),
 * @OA\Response(response="404",description="Post not found")
 * )
 * 
 ** @OA\DELETE(
 *     path="/v1/posts/{id}",
 *     summary="Delete post by ID",
 *     description="Delete post",
 *     operationId="deletePostById",
 *     tags={"Post"},
 *     @OA\Parameter(
 *         description="ID of post to delete",
 *         in="path",
 *         name="id",
 *         required=true,
 *         @OA\Schema(
 *           type="integer",
 *           format="int64"
 *         )
 *     ),
 * security={{"bearerAuth":{}}}, 
 * 
 * @OA\Response(response=204, description="No Content"),
 * @OA\Response(response=422, description="Unprocessable Entity"),
 * @OA\Response(response="404",description="Post not found")
 * )
 */
    public $modelClass = 'app\models\Post';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['class'] = HttpBearerAuth::className();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();

        // отключить действие "create"
        unset($actions['create']);

        return $actions;
    }

    public function actionCreate()
    {        
            $model = new Post(['scenario' => Post::SCENARIO_POST_CREATE]);
            
            // IF model load and model save
            if( $model->load(Yii::$app->getRequest()->getBodyParams(), '') && $model->save()){

                Yii::$app->response->statusCode = 201;
                return $model;
                
            }else{
                Yii::$app->response->statusCode = 422;
                return $model->getErrors();
            }
            
    }

}
