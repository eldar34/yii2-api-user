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
    public function actionLogin()
    {
        $model = new LoginForm();
        
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        
        if ($token = $model->auth()) {
            return $token;
        } else {
            return $model->getErrors();
        }
    }

}
