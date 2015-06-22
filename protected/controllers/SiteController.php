<?php
class SiteController extends Controller
{
	public function filters()
    {
		return array(
			'accessControl',
		);
    }

	public function accessRules()
	{
		return array(
			array('allow',
				'actions' => array('index', 'error'),
				'users' => array('*'),
			),
			array('allow',
				'actions' => array('register', 'UserRegistration', 'UserRegistrationSuccessfully', 'login', 'ForgotPassword', 'ChandePassword'),
				'users' => array('?'),
			),
			array('allow',
				'actions' => array('logout'),
				'users' => array('@'),
			),
			array('deny',
				'users' => array('*'),
			)
		);
	}

	public function actionIndex()
	{
		$this->redirect(Yii::app()->user->isGuest ? '/site/login' : '/admin/dashboard');
	}

	public function actionUserRegistrationSuccessfully()
	{
		$this->render('userRegistrationSuccessfully');
	}

	public function actionError()
	{
		if($error = Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
			{
				echo $error['message'];
			}
			else
			{
				$this->render('error', $error);
			}
		}
	}

	public function actionLogin()
	{
		$model = new LoginForm;
		if(isset($_POST['ajax']) && ($_POST['ajax'] === 'login-form'))
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		if(isset($_POST['LoginForm']))
		{
			$model->attributes = $_POST['LoginForm'];
			if($model->validate() && $model->login())
			{
				$this->redirect('/admin/dashboard');
			}
		}
		$this->render('login', array('model' => $model));
	}

	public function actionRegister()
	{
		$model = new RegisterForm();
		if(isset($_POST['ajax']) &&($_POST['ajax'] === 'register-form'))
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		if(isset($_POST['RegisterForm']))
		{
			$model->attributes = $_POST['RegisterForm'];
			if($model->validate() && $model->register())
			{
				$model = new LoginForm;
				$model->attributes = $_POST['RegisterForm'];
				
				if($model->validate() && $model->login())
				{
					$this->redirect('/admin/dashboard');
				}
			}
		}
		if (isset($_GET['code']) && $model->validateCode($_GET['code']))
		{
			$request = Request::model()->find('code=:code', array(':code' => $_GET['code']));
			$model->email = $request->email;
		}
		else
		{
			throw new CHttpException(403, 'You do not have access to this page.');
		}
		$this->render('register', array('model' => $model));
	}

	public function actionUserRegistration()
	{
		$model = new RegisterForm();
		if(isset($_POST['ajax']) &&($_POST['ajax'] === 'register-form'))
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		if(isset($_POST['RegisterForm']))
		{
			$model->attributes = $_POST['RegisterForm'];

			if($model->validate() && $model->registerUser())
			{
				$this->redirect('/site/userRegistrationSuccessfully');
			}
		}
		$this->render('register', array('model' => $model));
	}

	public function actionChandePassword()
	{
		$model = new ChangePasswordForm();

		if (isset($_GET['code']) && $model->validateCode($_GET['code']))
		{
			if(isset($_POST['ChangePasswordForm']))
			{
				$model->attributes = $_POST['ChangePasswordForm'];

				if($model->validate() && $model->change())
				{
					$this->redirect(Yii::app()->request->getBaseUrl(true));
				}
			}
		}
		else
		{
			throw new CHttpException(403, 'You do not have access to this page.');
		}

		$this->render('changePassword', array('model' => $model));
	}

	public function actionForgotPassword()
	{
		$model = new ForgotPassword();

		if(isset($_POST['ForgotPassword']))
		{
			$model->attributes = $_POST['ForgotPassword'];
			if($model->save())
			{
				$this->redirect(Yii::app()->request->getBaseUrl(true));
			}
		}
		$this->render('forgotPassword', array('model' => $model));
	}
	
	public function actionLogout()
	{
		Yii::app()->user->logout();
        unset(Yii::app()->session['userName']);
		$this->redirect(Yii::app()->homeUrl);
	}
}