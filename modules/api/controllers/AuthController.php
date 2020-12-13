<?php

namespace app\modules\api\controllers;

use Yii;
use yii\rest\Controller;
use app\models\LoginForm;
use yii\web\Response;


/**
 * Auth controller for the `api` module
 */
class AuthController extends Controller
{

    protected function verbs()
    {
        return [
            'login' => ['post'],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */

/**
 * @OA\POST(path="/web/v1/auth/login", tags={"Auth"},
 *      @OA\RequestBody(
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  required={"username", "password"},
 *                  @OA\Property(property="username", type="string"),
 *                  @OA\Property(property="password", type="string")
 *              )
 *          )
 *      ),
 * @OA\Response(response=200, description="Successful operation"),
 * @OA\Response(response=403, description="Forbidden")
 * )
*/
    public function actionLogin()
    {
        $model = new LoginForm();
        
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        
        if ($token = $model->auth()) {
            return $token;
        } else {
            Yii::$app->response->statusCode = 403;
            return $model->getErrors();
        }
    }

}
