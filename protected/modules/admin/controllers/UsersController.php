<?php
class UsersController extends Controller
{
	public function filters()
	{
		return array(
			'accessControl',
			'postOnly + delete',
		);
	}

	public function accessRules()
	{
		return array(
			array('allow',
				'actions' => array('view'),
				'users' => array('@'),
			),
			array('allow',
				'actions' => array('update'),
				'users' => array('@'),
				'expression' => '(Yii::app()->user->id == ($_GET[\'id\']) || Yii::app()->user->checkAccess(\'admin\'))',
			),
			array('allow',
				'actions' => array('admin', 'add', 'delete'),
				'roles' => array('admin'),
			),
			array('deny',
				'users' => array('*'),
			),
		);
	}

	public function actionView($id)
	{
		$this->render('view', array(
			'model' => $this->loadModel($id),
		));
	}

	public function actionAdd()
	{
		$model = new Requests;
		if(isset($_POST['Requests']))
		{
			$model->attributes = $_POST['Requests'];
			if($model->save())
			{
				$this->redirect(Yii::app()->request->urlReferrer);
			}
		}
		$this->render('add', array(
			'model' => $model,
		));
	}

	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);
        if ($model->authAssignment->itemname == 'organizer')
        {
            $model->organizerName = $this->getOrganizer($model->id);
        }
		if(isset($_POST['Users']))
		{
			$model->attributes = $_POST['Users'];
			$model->save();
		}
		$this->render('update', array(
			'model' => $model,
		));
	}
    
    private function getOrganizer($userId)
	{
		$organizer = OrganizersDraft::model()->find(array(
			'select' => 'title',
			'condition' => 'userId=:userId',
            'params' => array('userId' => $userId),
		)); 
		return $organizer['title'];
	}

	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();
		if(!isset($_GET['ajax']))
		{
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
	}

	public function actionIndex()
	{
		$dataProvider = new CActiveDataProvider('Users');
		$this->render('index', array(
			'dataProvider' => $dataProvider,
		));
	}

	public function actionAdmin()
	{
		$model = new Users('search');
		$model->unsetAttributes();
		$model->state = 'active';
		if(isset($_GET['Users']))
		{
			$model->attributes = $_GET['Users'];
		}
		$this->render('admin', array('model' => $model));
	}

	public function loadModel($id)
	{
		$model = Users::model()->findByPk($id);
		if($model === null)
		{
			throw new CHttpException(404, 'The requested page does not exist.');
		}
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && ($_POST['ajax'] === 'users-form'))
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
