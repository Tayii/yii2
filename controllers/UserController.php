<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBasicAuth;

class UserController extends ActiveController
{
	 public $modelClass = 'app\models\User';
/*
	    public function behaviors()
	    {
		return [
		    'access' => [
		        'class' => AccessControl::className(),
		        'only' => ['update', 'delete'],
		        'rules' => [
		            [
		                'allow' => true,
		                'actions' => ['update', 'delete'],
		                'roles' => ['@'],
		            ],
		        ],
		    ],
		];
	    }
*/
	public function actions()
	{
	    $actions = parent::actions();

	    // disable the "delete" and "create" actions
	    unset($actions['create'], $actions['delete']);

	    return $actions;
	}
/*
	public function actionView($id)
	{
	    return User::findOne($id);
	}

	/**
	 * Checks the privilege of the current user.
	 *
	 * This method should be overridden to check whether the current user has the privilege
	 * to run the specified action against the specified data model.
	 * If the user does not have access, a [[ForbiddenHttpException]] should be thrown.
	 *
	 * @param string $action the ID of the action to be executed
	 * @param \yii\base\Model $model the model to be accessed. If `null`, it means no specific model is being accessed.
	 * @param array $params additional parameters
	 * @throws ForbiddenHttpException if the user does not have access
	 */
	public function checkAccess($action, $model = null, $params = [])
	{
	    // check if the user can access $action and $model
	    // throw ForbiddenHttpException if access should be denied
	    if ($action === 'update') {
		if (\Yii::$app->user && $model->id === \Yii::$app->user->id) {
			return;
		}
		parse_str(file_get_contents("php://input"),$_GET);
		if (isset($_GET['auth_key']) && $model->auth_key === $_GET['auth_key']) {
			return;
		}
	    }
	    else return;
		throw new \yii\web\ForbiddenHttpException(sprintf('You can only %s your account.', $action));
	}
}
