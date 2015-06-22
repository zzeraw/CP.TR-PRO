<?php
class PlacesDraftController extends Controller
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
				'actions' => array('admin', 'add', 'view', 'update'),
				'roles' => array('editor'),
			),
			array('allow',
				'actions' => array('delete'),
				'roles' => array('moderator'),
			),
			array('allow',
				'actions' => array('removed', 'restore'),
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
		$model = new PlacesDraft;
		if(isset($_POST['PlacesDraft']))
		{
			$model->attributes = $_POST['PlacesDraft'];
			$model->formProcessing = true;
			if($model->save())
			{
				$this->redirect($model->redirectUrl);
			}
		}
		$this->render('add', array(
			'model' => $model,
		));
	}
	
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);
		if(isset($_POST['PlacesDraft']))
		{
			$model->attributes = $_POST['PlacesDraft'];
			$model->formProcessing = true;
			if($model->save())
			{
				$this->redirect($model->redirectUrl);
			}
		}
		$this->render('update', array(
			'model' => $model,
		));
	}

	public function actionDelete($id)
	{
		$exists = PlacesPublish::model()->findAll('id=:id', array(':id' => $id));
		if (!$exists)
		{
			$this->removePlaceDraft($id);
		}
		else
		{
			throw new CHttpException(403, Yii::t('main', 'Published place can not be deleted.'));
		}
	}

	public function actionRestore($id)
	{
		$this->restorePlaceDraft($id);
		$this->redirect(Yii::app()->request->urlReferrer);	
	}		

	public function actionIndex()
	{
		if (Yii::app()->user->checkAccess('moderator'))
		{
			$dataProvider = new CActiveDataProvider('PlacesDraft');
		}
		else
		{
			$dataProvider = new CActiveDataProvider('PlacesDraft', array(
				'criteria' => array(
					'condition' => 'authorId = '.Yii::app()->user->getId(),
				),
			));
		}
		$this->render('index', array(
			'dataProvider' => $dataProvider,
		));
	}

	public function actionAdmin()
	{
		$model = new PlacesDraft('search');
		$model->unsetAttributes();
		if(isset($_GET['PlacesDraft']))
		{
			$model->attributes = $_GET['PlacesDraft'];
		}
		$this->render('admin', array(
			'model' => $model,
		));
	}

	public function actionRemoved()
	{
		$model = new PlacesDraft('search');
		$model->unsetAttributes();
		if(isset($_GET['PlacesDraft']))
		{
			$model->attributes = $_GET['PlacesDraft'];
		}
		$this->render('adminRemoved', array(
			'model' => $model,
		));
	}

	public function loadModel($id)
	{
		$model = PlacesDraft::model()->findByPk($id);
		if($model === null)
		{
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}
	
	private function removePlaceDraft($id)
	{
		$model = $this->loadModel($id);
		$model->setScenario('ignore');
		$model->state = 'removed';
		$model->save();
	}
	
	private function restorePlaceDraft($id)
	{
		$model = $this->loadModel($id);
		$model->setScenario('ignore');
		$model->state = 'draft';
		$model->save();
	}
}
