<?php
class CategoriesController extends Controller
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
				'actions' => array('admin', 'add', 'view', 'update', 'delete'),
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
		$model = new Categories;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST['Categories']))
		{
			$model->attributes = $_POST['Categories'];
			if($model->save())
			{
				$this->redirect('/admin/categories/admin');
			}
		}
		$this->render('add', array(
			'model' => $model,
		));
	}

	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST['Categories']))
		{
			$model->attributes = $_POST['Categories'];
			if($model->save())
			{
				$this->redirect('/admin/categories/admin');
			}
		}
		$this->render('update', array(
			'model' => $model,
		));
	}

	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		if(!isset($_GET['ajax']))
		{
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
	}

	public function actionAdmin()
	{
		$model = new Categories('search');
		$model->unsetAttributes();
		if(isset($_GET['Categories']))
		{
			$model->attributes = $_GET['Categories'];
		}
		$this->render('admin', array(
			'model' => $model,
		));
	}

	public function loadModel($id)
	{
		$model = Categories::model()->findByPk($id);
		if($model === null)
		{
			throw new CHttpException(404, 'The requested page does not exist.');
		}
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && ($_POST['ajax'] === 'categories-form'))
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
