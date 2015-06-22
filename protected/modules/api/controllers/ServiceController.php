<?php
class ServiceController extends Controller
{
	public function filters()
    {
		return array(
						array(
								'HttpAuthFilter',
								'users'=>array(Yii::app()->params['api']['username']=>Yii::app()->params['api']['password']),
								'realm'=>'Admin section'
							),
						'accessControl',
				);
    }

	public function actionIndex()
	{
		$startdate = Yii::app()->request->getParam('startdate', '2000-03-31');
		$enddate = Yii::app()->request->getParam('enddate', '2000-03-31');

		$events = EventsPublish::model()->findAll('(eventDate >= :start) AND (eventDate < :end)', array(':start'=>$startdate, ':end'=>$enddate));

		echo CJSON::encode($events);
	}
}