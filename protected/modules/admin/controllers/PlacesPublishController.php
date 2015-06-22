<?php
class PlacesPublishController extends Controller
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
				'actions' => array('index', 'view'),
				'users' => array('@'),
			),
			array('allow',
				'actions' => array('admin'),
				'roles' => array('editor'),
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

	public function actionIndex()
	{
		$dataProvider = new CActiveDataProvider('PlacesPublish');
		$dataProvider->sort->defaultOrder = 'id DESC';
		$this->render('index', array(
			'dataProvider' => $dataProvider,
		));
	}

	public function actionAdmin()
	{
		$model = new PlacesPublish('search');
		$model->unsetAttributes();
		if(isset($_GET['PlacesPublish']))
		{
			$model->attributes = $_GET['PlacesPublish'];
		}
		$this->render('admin', array(
			'model' => $model,
		));
	}

	public function loadModel($id)
	{
		$model = PlacesPublish::model()->findByPk($id);
		if($model === null)
		{
			throw new CHttpException(404, 'The requested page does not exist.');
		}
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && ($_POST['ajax'] === 'places-publish-form'))
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
