<?php

namespace app\controllers;

use Yii;
use yii\helpers\Html;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\RegistrationForm;
use app\models\LinkForm;
use app\models\Users;
use app\models\Usersss;

class SiteController extends Controller
{
    /**
     * @inheritdoc
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
     * @inheritdoc
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
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
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
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
	
	
	public function actionRegistration()
	{

		Yii::$app->session->set('redir', '');
		$form = new RegistrationForm();
		$name='';
		$redir='';
		if($form->load(Yii::$app->request->post())&& $form->validate()){
			Yii::$app->session;	
			Yii::$app->session->set('login', $form->login);	
			Yii::$app->session->set('pass', $form->pass);	
			Yii::$app->session->set('name', $form->name);	
			Yii::$app->session->set('surname', $form->surname);	
			Yii::$app->session->set('email', $form->email);	
			$login = Html::encode($form->login);
			$pass = Html::encode($form->pass);
			$conf_pass = Html::encode($form->conf_pass);
			$name = Html::encode($form->name);
			$surname = Html::encode($form->surname);
			$email = Html::encode($form->email);
			
		}else{
			$login=null;
			$pass=null;
			$conf_pass=null;
			$name=null;
			$surname=null;
			$email=null;
		}
		
		$err=null;
		$users=null;
		$usersss=null;
		$modal_w=null;
		$check1 = Users::find()->where(['login' => $login])->all();
		$check2 = Usersss::find()->where(['login' => $login])->all();
		if ($check1) {
			if($pass==$conf_pass){
				$users = Users::find()->where(['login' => $login,'pass' => $pass])->one();
			}else{
				$err="Введіть однакові паролі";
			}
		}
		if ($check2) {
			if($pass==$conf_pass){
				$usersss = Usersss::find()->where(['login' => $login,'pass' => $pass])->one();
			}else{
				$err="Введіть однакові паролі";
			}
		}		
		if(($users<>null)and($usersss<>null)){
			$redir='1';
		}elseif($users<>null){
			$modal_w="+";
			Yii::$app->session->set('base', 'pgsql');	
		}elseif($usersss<>null){	
			$modal_w="+";
			Yii::$app->session->set('base', 'mysql');	
		}elseif($check1){
			$err="Цей логін уже зайнятий";			
		}elseif($check2){
			$err="Цей логін уже зайнятий";			
		}else{
			if($pass==$conf_pass){
				if($pass<>null){
					$modal_w=null;
					$users2 = new Users();
					$users2->login = Yii::$app->session->get('login');	
					$users2->pass = Yii::$app->session->get('pass');	
					$users2->user_name = Yii::$app->session->get('name');	
					$users2->user_surname = Yii::$app->session->get('surname');	
					$users2->email = Yii::$app->session->get('email');	
					$users2->save();
					
					$usersss2 = new Usersss();
					$usersss2->login = Yii::$app->session->get('login');	
					$usersss2->pass = Yii::$app->session->get('pass');	
					$usersss2->user_name = Yii::$app->session->get('name');	
					$usersss2->user_surname = Yii::$app->session->get('surname');	
					$usersss2->email = Yii::$app->session->get('email');	
					$usersss2->save();
					$redir='2';
				}
			}else{
				$err="Введіть однакові паролі";
			}
		}
		
		$model = new LinkForm();
		
		if($model->load(Yii::$app->request->post())&& $model->validate()){	
			$base = Yii::$app->session->get('base');
			if ($base=='pgsql'){
				$usersss2 = new Usersss();
				$usersss2->login = Yii::$app->session->get('login');	
				$usersss2->pass = Yii::$app->session->get('pass');	
				$usersss2->user_name = Yii::$app->session->get('name');	
				$usersss2->user_surname = Yii::$app->session->get('surname');	
				$usersss2->email = Yii::$app->session->get('email');	
				$usersss2->save();
			}elseif($base='mysql'){
				$users2 = new Users();
				$users2->login = Yii::$app->session->get('login');	
				$users2->pass = Yii::$app->session->get('pass');	
				$users2->user_name = Yii::$app->session->get('name');	
				$users2->user_surname = Yii::$app->session->get('surname');	
				$users2->email = Yii::$app->session->get('email');	
				$users2->save();
			}
		$massege= 'Дані синхронізовані';
		$redir='3';

		}
		Yii::$app->session->set('redir', $redir);	
		if(Yii::$app->session->get('redir')<>''){
			return $this->redirect(['/site/hello']);
		}
		$login2=Yii::$app->session->get('base');		
		return $this->render('registration',
		['model' => $model,	
		'form' => $form,	
		'login' => $login,
		'pass' => $pass,
		'name' => $name,
		'surname' => $surname,
		'email' => $email,
		'users' => $users,
		'usersss' => $usersss,
		'err' => $err,
		'modal_w' => $modal_w,
		]
		
		);
	}
	
	public function actionHello()
    {
	$name=Yii::$app->session->get('name');		
	$surname=Yii::$app->session->get('surname');		
		Yii::$app->session->set('redir', '');	

        return $this->render('Hello',
		[
		'name' => $name,
		'surname' => $surname,
		]
		);
    }

	
}
