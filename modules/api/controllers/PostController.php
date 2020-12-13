<?php

namespace app\modules\api\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\web\Response;

/**
 * Post controller for the `api` module
 */
/**
* @OA\Schemes(format="http")
* @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      in="header",
 *      name="Authorization",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
* )
*/
class PostController extends ActiveController
{

/**
 * 
 * @OA\Get(
 *      path="/web/v1/posts",
 *      tags={"Post"},
 *      description="Get list of posts",
 *      security={{"bearerAuth":{}}}, 
 * 
 *       @OA\Response(response="200", description="Posts list"),        
 *       @OA\Response(response=401, description="Unauthorized"),
 *       @OA\Response(response=404, description="Not Found"),
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

}
