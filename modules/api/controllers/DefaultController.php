<?php

namespace app\modules\api\controllers;

use yii\web\Controller;

/**
 * @OA\Info(title="Yii2-user-api", version="0.1")
 */



/**
 * Default controller for the `api` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
