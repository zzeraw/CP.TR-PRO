<?php
class EventsPublishController extends Controller
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
				'actions' => array('index', 'view', 'getTickets'),
				'users' => array('@'),
			),
			array('allow',
				'actions' => array('admin'),
				'roles' => array('editor'),
			),
			array('allow',
				'actions' => array('delete'),
				'roles' => array('moderator'),
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
	
	public function actionGetTickets($id)
	{
        $this->layout = 'tickets';
        $event = EventsPublish::model()->find('id=:id', array(':id' => $id));
        $tickets = $this->getTickets($id);
        $ticketsAmount = count($tickets);
        $columnsAmount = 4;
        $rowsAmount = ceil($ticketsAmount/$columnsAmount);
        $emptyCells = $rowsAmount * $columnsAmount - $ticketsAmount;
        for ($i = 0; $i < $ticketsAmount; ++$i)
        {
            $codes[$i % $rowsAmount][floor($i / $rowsAmount)] = $tickets[$i]->ticket;
        }
        for ($i = $ticketsAmount; $i < $ticketsAmount + $emptyCells; ++$i)
        {
            $codes[$i % $rowsAmount][floor($i / $rowsAmount)] = '&nbsp;';
        }
        $this->render('tickets', array(
            'codes' => $codes, 
            'event' => $event
        ));
	}
    
    private function getTickets($id)
    {
        $criteria = new CDbCriteria;
        $criteria->select = 'ticket';
        $criteria->condition = '(eventId=:eventId) AND (buy != 1)';
        $criteria->order = 'ticket';
        $criteria->params = array(':eventId' => $id);
        $data = Tickets::model()->findAll($criteria);
        return $data;
    }

    public function actionDelete($id)
	{
		$model = EventsDraft::model()->findByPk($id);
		$model->state = 'removed';
		$model->save(false);
		$this->loadModel($id)->delete();
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
		{
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
	}

	public function actionAdmin()
	{
		$model = new EventsPublish('search');
		$model->unsetAttributes();
		if(isset($_GET['EventsPublish']))
		{
			$model->attributes = $_GET['EventsPublish'];
		}
		$this->render('admin', array(
			'model' => $model,
		));
	}

	public function loadModel($id)
	{
		$model = EventsPublish::model()->findByPk($id);
		if($model === null)
		{
			throw new CHttpException(404, 'The requested page does not exist.');
		}
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && ($_POST['ajax'] === 'events-publish-form'))
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
