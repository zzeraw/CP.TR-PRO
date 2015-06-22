<?php
/**
 * Properties:
 * @property string $id
 * @property string $userId
 * @property string $title
 * @property string $contacts
 * @property string $state
 * @property string $updated
 * 
 * @property string $userName
 * @property string $formProcessing
 *
 * Relations:
 * @property EventsDraft[] $eventsDraft
 * @property EventsPublish[] $eventsPublish
 * @property Users $users
 * @property OrganizersPublish $organizersPublish
 */
class OrganizersDraft extends CActiveRecord
{
	public $userName;
	public $formProcessing = false;
	public $redirectUrl;
	
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return Yii::app()->params['tables']['organizersDraft'];
	}
	
	public function rules()
	{
		return array(
			array('title', 'required', 'except' => 'ignore'),
			array('title', 'length', 'max' => 255),
			array('userName, state, contacts, updated', 'safe'),
			array('userName, title, state, updated', 'safe', 'on' => 'search'),
		);
	}

	public function relations()
	{
		return array(
			'eventsDraft' => array(self::HAS_MANY, 'EventsDraft', 'organizerId'),
			'eventsPublish' => array(self::HAS_MANY, 'EventsPublish', 'organizerId'),
			'users' => array(self::BELONGS_TO, 'Users', 'userId'),
			'organizersPublish' => array(self::HAS_ONE, 'OrganizersPublish', 'id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'title' => Yii::t('main', 'Title'),
			'contacts' => Yii::t('main', 'Contacts'),
			'userName' => Yii::t('main', 'User name'),
			'state' => Yii::t('main', 'State'),
			'updated' => Yii::t('main', 'Updated'),
		);
	}
	
	public function beforeSave()
	{
		if ($this->formProcessing)
		{
			$this->setDefaultRedirect();
			$this->convertFormAttributes();
			if ($this->hasErrors())
			{
				return false;
			}
		}
		$this->updated = date('Y-m-d H:i:s');
		return parent::beforeSave();
	}
	
	public function afterSave()
	{
		if ($this->formProcessing && ($this->state == 'approved'))
		{
			$this->saveOrganizersPublish();
			$this->setRedirectToOrganizerPublish();
		}
		return parent::afterSave();
	}
	
	public function getUserLink()
	{
		if ($this->userId == null)
		{
			return;
		}
		return '<a href="../users/view/id/'.$this->userId.'">'.$this->users->displayName.'</a>';
	}
	
	public function getUserName($userId)
	{
		$user = Users::model()->find(array(
			'select' => 'displayName',
			'condition' => 'id=:id',
            'params' => array(':id' => $userId),
		));
		return $user['displayName'];
	}
    
    public function search()
	{
		$criteria = new CDbCriteria;
        $criteria->condition = 't.state != \'removed\'';
        return $this->searchTemplate($criteria);
	}
	
	public function searchRemoved()
	{
		$criteria = new CDbCriteria;
		$criteria->condition = 't.state = \'removed\'';
        return $this->searchTemplate($criteria);
	}
        
    public function searchModerator()
	{
		$criteria = new CDbCriteria;
        $criteria->condition = 't.state = \'moderation\'';
        return $this->searchTemplate($criteria);
	}

    private function searchTemplate($criteria)
    {
		$criteria->with = $this->getRelations();
		$criteria->together = true;
        $criteria->order = 'updated DESC';
		$criteria->compare('users.displayName', $this->userName, true);
		$criteria->compare('title', $this->title, true);
		$criteria->compare('contacts', $this->contacts, true);
		$criteria->compare('t.state', $this->state, true);
		$criteria->compare('updated', Helper::unformatDate($this->updated), true);
		$sort = $this->getSort();
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => $sort,
			'pagination' => array(
				'pageSize'  => Yii::app()->params['pagination']['pageSize'],
			),
		));
    }
    
    private function getRelations()
    {
        return array(
			'users' => array(
				'select' => array('displayName'),
			), 
		);
    }
    
    private function getSort()
    {
        $sort = new CSort();
		$sort->attributes = array(
			'userName' => array(
				'asc' => 'users.displayName ASC',
				'desc' => 'users.displayName DESC',
			),
			'*',
		);
        return $sort;
    }
	
	private function saveOrganizersPublish()
	{
		$organizerPublish = new OrganizersPublish();
        $organizerPublish->isNewRecord = !OrganizersPublish::model()->exists('id=:id', array(':id' => $this->id));
		$organizerPublish->id = $this->id;
		$organizerPublish->userId = $this->userId;
		$organizerPublish->title = $this->title;
		$organizerPublish->contacts = $this->contacts;
		$organizerPublish->save();
	}
	
	private function convertFormAttributes()
	{
		$this->userId = $this->getUserId($this->userName);
        if ($this->userIsOrganizer($this->userId))
        {
            $this->addError('userName', Yii::t('main', 'For this the user has already secured organizer'));
        }
		$this->state = $this->parseButtonValue($this->state);		
	}
    
    private function userIsOrganizer($userId)
    {
        return OrganizersPublish::model()->exists('userId=:userId', array(':userId' => $userId));
    }

    private function getUserId($name)
	{
		$user = Users::model()->find(array(
			'select' => 'id',
			'condition' => 'displayName=:displayName',
			'params' => array(':displayName' => $name),
		));
		return $user['id'];
	}
	
	private function setDefaultRedirect()
	{
		$this->redirectUrl = '/admin/organizersDraft/admin';
	}
	
	private function setRedirectToOrganizerPublish()
	{
		$this->redirectUrl = '/admin/organizersPublish/admin';
	}
	
	private function parseButtonValue($value)
	{
		switch ($value)
		{
			case 'Save draft':
			case 'Update draft':
				return 'draft';
			case 'Publish organizer':
				return 'approved';
			case 'Send to moderate':
				return 'moderation';
		}
	}
}