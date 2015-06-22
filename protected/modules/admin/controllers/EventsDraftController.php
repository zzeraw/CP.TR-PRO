<?php
class EventsDraftController extends Controller
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
				'actions' => array('admin', 'add', 'update', 'sendForModeration'),
				'roles' => array('editor'),
			),
			array('allow',
				'actions' => array('delete'),
				'users' => array('*'),
				'expression' => '(Yii::app()->controller->isCreator($_GET[\'id\']) || Yii::app()->user->checkAccess(\'moderator\'))',
			),
			array('allow',
				'actions' => array('removed', 'restore'),
				'roles' => array('admin'),
			),
			array('allow',
				'actions' => array('autocompletePlace', 'autocompleteOrganizer', 'autocompleteCategory'),
				'users' => array('*'),
			),
			array('deny',
				'users' => array('*'),
			),
		);
	}
	
	public function isCreator($eventId)
	{
		$model = EventsDraft::model()->findByPk($eventId);
		return (Yii::app()->user->id == $model->authorId);
	}

	public function actionAdd()
	{
		$model = new EventsDraft;
		if(isset($_POST['EventsDraft']))
		{
			$model->attributes = $_POST['EventsDraft'];
			if (Yii::app()->user->checkAccess('organizer'))
			{
				$model->organizerName = $model->getOrganizerNameByUserId(Yii::app()->user->getId());
			}
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
		$model->placeName = $model->getPlaceName($model->placeId);
		$model->organizerName = $model->getOrganizerName($model->organizerId);
		$model->categories = $model->getCategories().', ';
		$model->eventDate = Helper::formatDate($model->eventDate);
		if(isset($_POST['EventsDraft']))
		{
			$model->attributes = $_POST['EventsDraft'];
			$model->formProcessing = true;
			if ($model->save())
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
		$existsEventPublish = EventsPublish::model()->exists('id='.$id);
		if (!$existsEventPublish)
		{
			$model = $this->loadModel($id);
			$model->state = 'removed';
			$model->save(false);
			$this->redirect(Yii::app()->request->urlReferrer);
		}
		else
		{
			throw new CHttpException(403, Yii::t('main', 'Published event can not be deleted.'));
		}
	}

	public function actionRestore($id)
	{
		$model = $this->loadModel($id);
		$model->state = 'draft';
		$model->save(false);
		$this->redirect(Yii::app()->request->urlReferrer);	
	}	
	
	public function actionSendForModeration($id)
	{
		$model = $this->loadModel($id);
		if ($model->authorId != Yii::app()->user->getId())
		{
			throw new CHttpException(403, 'You do not have access to this page.');
			return;
		}
		$model->state = 'moderation';
		$model->save(false);
		$this->redirect(Yii::app()->request->urlReferrer);
	}

	public function actionAdmin()
	{		
		$model = new EventsDraft('search');
		$model->unsetAttributes();
		if(isset($_GET['EventsDraft']))
		{
			$model->attributes = $_GET['EventsDraft'];
		}
		$this->render('admin', array(
			'model' => $model,
		));
	}
	
	public function actionRemoved()
	{
		$model = new EventsDraft('search');
		$model->unsetAttributes();
		if(isset($_GET['EventsDraft']))
		{
			$model->attributes = $_GET['EventsDraft'];
		}
		$this->render('adminRemoved', array(
			'model' => $model,
		));
	}

	public function actionAutocompletePlace()
	{
		$term = Yii::app()->getRequest()->getParam('term');
		if(Yii::app()->request->isAjaxRequest)
		{
			$result = array();
			$sql = sprintf('SELECT `title` FROM `%s` WHERE title LIKE \'%%%s%%\'', 
				Yii::app()->params['tables']['placesPublish'],
				$term
			);
			$places = Yii::app()->db->createCommand($sql)->queryAll();
			foreach($places as $place) 
			{
				array_push($result, $place['title']);
			}
			$sql = sprintf('SELECT `title` FROM `%s` WHERE title LIKE \'%%%s%%\' AND NOT `id` IN (SELECT `id` FROM `%s`)', 
				Yii::app()->params['tables']['placesDraft'],
				$term,
				Yii::app()->params['tables']['placesPublish']
			);
			$places = Yii::app()->db->createCommand($sql)->queryAll();
			foreach($places as $place) 
			{
				array_push($result, $place['title']);
			}
			echo CJSON::encode($result);
			Yii::app()->end();
		}
	}
	
	public function actionAutocompleteOrganizer()
	{
		$term = Yii::app()->getRequest()->getParam('term');
		if(Yii::app()->request->isAjaxRequest)
		{
			$result = array();
			$sql = sprintf('SELECT `title` FROM `%s` WHERE title LIKE \'%%%s%%\'', 
				Yii::app()->params['tables']['organizersPublish'],
				$term
			);
			$organizers = Yii::app()->db->createCommand($sql)->queryAll();
			foreach($organizers as $organizer) 
			{
				array_push($result, $organizer['title']);
			}
			$sql = sprintf('SELECT `title` FROM `%s` WHERE title LIKE \'%%%s%%\' AND NOT `id` IN (SELECT `id` FROM `%s`)', 
				Yii::app()->params['tables']['organizersDraft'],
				$term,
				Yii::app()->params['tables']['organizersPublish']
			);
			$organizers = Yii::app()->db->createCommand($sql)->queryAll();
			foreach($organizers as $organizer) 
			{
				array_push($result, $organizer['title']);
			}
			echo CJSON::encode($result);
			Yii::app()->end();
		}
	}
	
	public function actionAutocompleteCategory()
	{
		$term = Yii::app()->getRequest()->getParam('term');
		if (!Yii::app()->request->isAjaxRequest)
		{
			return;
		}
		$condition = ($term == null) ? array() : array('condition' => 'title LIKE \'%'.$term.'%\'');
		$categories = Categories::model()->findAll($condition);
		$result = array();
		foreach($categories as $category) 
		{
			array_push($result, $category['title']);
		}
		echo CJSON::encode($result);
		Yii::app()->end();
	}

	public function loadModel($id)
	{
		$model = EventsDraft::model()->findByPk($id);
		if($model === null)
		{
			throw new CHttpException(404, 'The requested page does not exist.');
		}
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && ($_POST['ajax'] === 'events-draft-form'))
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
