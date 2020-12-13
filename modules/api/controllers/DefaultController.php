<?php

namespace app\modules\api\controllers;

use yii\web\Controller;

/**
 * @OA\Info(title="My Second API", version="0.1")
 */
 
/**
 * @OA\Get(
 *     path="/api/resource.json",
 *     @OA\Response(response="200", description="An example resource")
 * )
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
