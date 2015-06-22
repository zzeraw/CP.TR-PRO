<?php
class DashboardController extends Controller
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
				'actions' => array('index'),
				'roles' => array('admin', 'editor', 'moderator'),
			),
			array('deny',
				'users' => array('*'),
			),
		);
	}
	
	public function actionIndex()
	{
		$pageName = Helper::getDashboardPageName();
		call_user_func(array('DashboardController', 'show'.$pageName.'Dashboard'));
	}
	
	private function showEditorDashboard()
	{
		$model = new EventsDraft('search');
		$model->unsetAttributes();
		if(isset($_GET['EventsDraft']))
		{
			$model->attributes = $_GET['EventsDraft'];
		}
		$this->render('editor', array(
			'model' => $model,
		));
	}
	
	private function showModeratorDashboard()
	{
		$eventsModel = new EventsDraft('search');
		$placesModel = new PlacesDraft('search');
        $organizersDraft = new OrganizersDraft('search');
		$this->render('moderator', array(
			'eventsModel' => $eventsModel,
			'placesModel' => $placesModel,
            'organizersDraft' => $organizersDraft
		));
	}
	
	private function showAdminDashboard()
	{
		$users = new Users('search');
		$this->render('admin', array(
			'statisticsModel' => $this->getStatisticsDataProvider(),
			'usersModel' => $users,
		));
	}
	
	private function getStatisticsDataProvider()
	{
		$items = array();
		$amount = Yii::app()->db->createCommand('SELECT count(`id`) as `amount` FROM `'.Yii::app()->params['tables']['users'].'`')->queryScalar();
		array_push($items, array('title' => Yii::t('main', 'Users amount'), 'value' => $amount));
		$amount = Yii::app()->db->createCommand('SELECT count(`id`) as `amount` FROM `'.Yii::app()->params['tables']['categories'].'`')->queryScalar();
		array_push($items, array('title' => Yii::t('main', 'Categories amount'), 'value' => $amount));
		$amount = Yii::app()->db->createCommand('SELECT count(`id`) as `amount` FROM `'.Yii::app()->params['tables']['eventsPublish'].'`')->queryScalar();
		array_push($items, array('title' => Yii::t('main', 'Events publish amount'), 'value' => $amount));
		$amount = Yii::app()->db->createCommand('SELECT count(`id`) as `amount` FROM `'.Yii::app()->params['tables']['eventsDraft'].'`')->queryScalar();
		array_push($items, array('title' => Yii::t('main', 'Events draft amount'), 'value' => $amount));
		$amount = Yii::app()->db->createCommand('SELECT count(`id`) as `amount` FROM `'.Yii::app()->params['tables']['placesPublish'].'`')->queryScalar();
		array_push($items, array('title' => Yii::t('main', 'Places publish amount'), 'value' => $amount));
		$amount = Yii::app()->db->createCommand('SELECT count(`id`) as `amount` FROM `'.Yii::app()->params['tables']['placesDraft'].'`')->queryScalar();
		array_push($items, array('title' => Yii::t('main', 'Places draft amount'), 'value' => $amount));
		$amount = Yii::app()->db->createCommand('SELECT count(`id`) as `amount` FROM `'.Yii::app()->params['tables']['organizersDraft'].'`')->queryScalar();
		array_push($items, array('title' => Yii::t('main', 'Organizers draft amount'), 'value' => $amount));
		$amount = Yii::app()->db->createCommand('SELECT count(`id`) as `amount` FROM `'.Yii::app()->params['tables']['organizersPublish'].'`')->queryScalar();
		array_push($items, array('title' => Yii::t('main', 'Organizers publish amount'), 'value' => $amount));
		return new CArrayDataProvider($items, array('keyField' => 'title')); 
	}
}