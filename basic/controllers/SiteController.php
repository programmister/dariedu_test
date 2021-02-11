<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\widgets\ActiveForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string|array
     */
    public function actionIndex()
    {
	    $model = new ContactForm();
	    if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
		    /*Yii::$app->session->setFlash('contactFormSubmitted');
			return $this->refresh();*/
		    $model->scenario = 'insert';
		    Yii::$app->response->format = Response::FORMAT_JSON;
		    $res = ActiveForm::validate($model);
		    if (!$res){
		    	if ($model->save(false)) Yii::$app->session->setFlash('contactFormSubmitted');
		    }
		    return $res;
	    }
	    return $this->render('contact', [
		    'model' => $model,
	    ]);
    }

	public function actionUpdate($id)
	{
		$model = new ContactForm();
		if ($model->load(Yii::$app->request->post())) {
			$model->scenario = 'update';
			$res = ActiveForm::validate($model);
		    if (!$res){
			    $model->update(false);
			    return $this->redirect(['lead']);
		    }else{
			    Yii::$app->response->format = Response::FORMAT_JSON;
		    	return $res;
		    }
		}else{
			if ($id){
				$model = $model->findOne($id);
			}
		}
		return $this->renderAjax('contact', [
			'model' => $model,
			'edit' => true
		]);
	}

	public function actionDelete($id)
	{
		//$this->findModel($id)->delete();
		ContactForm::findOne($id)->delete();

		if (Yii::$app->request->isAjax)
			return $this->renderList();
		else
			return $this->redirect(['lead']);
	}

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string|array
     */
    public function actionContact()
    {
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionLead()
    {
	    if (Yii::$app->user->isGuest) return $this->redirect(['login']);
    	$searchModel = new ContactForm();
	    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
	    return $this->render('lead', [
		    'dataProvider' => $dataProvider,
		    'searchModel' => $searchModel,
	    ]);
    }
}
