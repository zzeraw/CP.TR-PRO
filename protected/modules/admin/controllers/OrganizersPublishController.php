<?php
class OrganizersPublishController extends Controller
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
				'actions' => array('admin', 'view'),
				'roles' => array('editor'),
			),
			array('allow',
				'actions' => array('delete'),
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

	public function actionDelete($id)
	{
		$model = OrganizersDraft::model()->findByPk($id);
		$model->state = 'removed';
		$model->save(false);
		
		$this->loadModel($id)->delete();
		if(!isset($_GET['ajax']))
		{
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
	}

	public function actionAdmin()
	{
		$model = new OrganizersPublish('search');
		$model->unsetAttributes();
		if(isset($_GET['OrganizersPublish']))
		{
			$model->attributes = $_GET['OrganizersPublish'];
		}
		$this->render('admin', array(
			'model' => $model,
		));
	}

	public function loadModel($id)
	{
		$model = OrganizersPublish::model()->findByPk($id);
		if($model === null)
		{
			throw new CHttpException(404, 'The requested page does not exist.');
		}
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && ($_POST['ajax'] === 'organizers-publish-form'))
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
