<?php
class OrganizersDraftController extends Controller
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
				'actions' => array('add', 'update', 'view', 'admin'),
				'roles' => array('editor'),
			),
			array('allow',
				'actions' => array('autocompleteUserName'),
				'users' => array('*'),
			),
			array('allow',
				'actions' => array('delete'),
				'roles' => array('moderator'),
			),
			array('allow',
				'actions' => array('restore', 'removed'),
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
		$model = new OrganizersDraft;
		if(isset($_POST['OrganizersDraft']))
		{
			$model->attributes = $_POST['OrganizersDraft'];
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
		$model->userName = $model->getUserName($model->userId);
		if(isset($_POST['OrganizersDraft']))
		{
			$model->attributes = $_POST['OrganizersDraft'];
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
                $existsOrganizerPublish = OrganizersPublish::model()->exists('id='.$id);
                if (!$existsOrganizerPublish)
                {
                    $model = $this->loadModel($id);
                    $model->state = 'removed';
                    $model->save(false);
                    $this->redirect(Yii::app()->request->urlReferrer);
                }
                else
                {
                    throw new CHttpException(403, Yii::t('main', 'Published organizer can not be deleted.'));
                }
	}
	

	public function actionAdmin()
	{
		$model = new OrganizersDraft('search');
		$model->unsetAttributes();
		if(isset($_GET['OrganizersDraft']))
		{
			$model->attributes = $_GET['OrganizersDraft'];
		}
		$this->render('admin', array(
			'model' => $model,
		));
	}
	
	public function actionRestore($id)
	{
		$model = OrganizersDraft::model()->findByPk($id);
		$model->setScenario('ignore');
		$model->state = 'draft';
		$model->save();
		$this->redirect(Yii::app()->request->urlReferrer);	
	}	
	
	public function actionRemoved()
	{
		$model = new OrganizersDraft('search');
		$model->unsetAttributes();
		if(isset($_GET['OrganizersDraft']))
		{
			$model->attributes = $_GET['OrganizersDraft'];
		}
		$this->render('adminRemoved', array(
			'model' => $model,
		));
	}
	
	public function actionAutocompleteUserName()
	{
        if(!Yii::app()->request->isAjaxRequest)
        {
            return;
        }
		$term = Yii::app()->getRequest()->getParam('term');
        $users = $this->getUsers($term);
        $result = array();
        foreach($users as $user)
        {
            if (!OrganizersPublish::model()->exists('userId=:userId', array(':userId' => $user->id)))
            {
                array_push($result, $user->displayName);
            }
        }
        echo CJSON::encode($result);
        Yii::app()->end();
	}
    
    private function getUsers($userName)
    {
        $criteria = new CDbCriteria;
        $criteria->select = 'displayName';
        $criteria->with = 'authAssignment';
        $criteria->together = true;
        $criteria->compare('displayName', $userName, 1);
        $criteria->compare('authAssignment.itemname', '<>admin');
        return Users::model()->findAll($criteria);
    }

    public function loadModel($id)
	{
		$model = OrganizersDraft::model()->findByPk($id);
		if($model === null)
		{
			throw new CHttpException(404, 'The requested page does not exist.');
		}
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && ($_POST['ajax'] === 'organizers-draft-form'))
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
