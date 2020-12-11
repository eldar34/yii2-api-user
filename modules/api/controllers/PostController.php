<?php

namespace app\modules\api\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\web\Response;

/**
 * Post controller for the `api` module
 */
class PostController extends ActiveController
{
    public $modelClass = 'app\models\Post';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['class'] = HttpBearerAuth::className();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_HTML;

        return $behaviors;
    }

}
