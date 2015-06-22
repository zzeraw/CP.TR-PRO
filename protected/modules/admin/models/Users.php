<?php
/**
 * Properties:
 * @property string $id
 * @property string $email
 * @property string $password
 * @property string $displayName
 * @property string $socialNetwork
 * @property string $socialUserId
 * @property string $state
 *
 * @property string $role
 *
 * Relations:
 * @property Devices[] $devices
 * @property EventsDraft[] $eventsDraft
 * @property EventsPublish[] $eventsPublish
 * @property OrganizersDraft[] $organizersDraft
 * @property PlacesDraft[] $placesDraft
 * @property PlacesPublish[] $placesPublish
 * @property AuthAssignment $authAssignment
 */
class Users extends CActiveRecord
{
	public $role;
	public $organizerName;

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return Yii::app()->params['tables']['users'];
	}

	public function rules()
	{
		return array(
			array('email, displayName', 'required'),
			array('email', 'checkEmail'),
			array('role, state, organizerName', 'safe'),
			array('email', 'email', 'message' => Yii::t('main', 'Wrong email')),
			array('email, password, displayName, socialNetwork, socialUserId', 'length', 'max' => 255),
			array('email, displayName, socialNetwork, socialUserId, state, role', 'safe', 'on' => 'search'),
		);
	}

	public function relations()
	{
		return array(
			'devices' => array(self::HAS_MANY, 'Devices', 'userId'),
			'eventsDraft' => array(self::HAS_MANY, 'EventsDraft', 'authorId'),
			'eventsPublish' => array(self::HAS_MANY, 'EventsPublish', 'authorId'),
			'organizersDraft' => array(self::HAS_MANY, 'OrganizersDraft', 'userId'),
			'placesDraft' => array(self::HAS_MANY, 'PlacesDraft', 'authorId'),
			'placesPublish' => array(self::HAS_MANY, 'PlacesPublish', 'authorId'),
			'authAssignment' => array(self::HAS_ONE, 'AuthAssignment', 'userid'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'email' => Yii::t('main', 'Email'),
			'password' => Yii::t('main', 'Password'),
			'displayName' => Yii::t('main', 'Display Name'),
			'socialNetwork' => Yii::t('main', 'Social Network'),
			'socialUserId' => Yii::t('main', 'Social User'),
			'state' => Yii::t('main', 'State'),
			'role' => Yii::t('main', 'Role'),
			'organizerName' => Yii::t('main', 'Organizer name'),
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;
        return $this->searchTemplate($criteria);
	}
	
	public function inactiveSearch()
	{
		$criteria = new CDbCriteria;
        $criteria->condition = 'state = \'inactive\'';
		return $this->searchTemplate($criteria);
	}
    
    private function searchTemplate($criteria)
    {
		$criteria->with = $this->getRelations();
		$criteria->together = true;
        $criteria->order = 't.id DESC';
		$criteria->compare('email', $this->email, true);
		$criteria->compare('displayName', $this->displayName, true);
		$criteria->compare('socialNetwork', $this->socialNetwork, true);
		$criteria->compare('socialUserId', $this->socialUserId, true);
		$criteria->compare('state', $this->state);
		$criteria->compare('authAssignment.itemname', $this->role);
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
			'authAssignment' => array(
				'select' => array('itemname'),
			), 
		);
    }
    
    private function getSort()
    {
        $sort = new CSort();
		$sort->attributes = array(
			'role' => array(
				'asc' => 'authAssignment.itemname ASC',
				'desc' => 'authAssignment.itemname DESC',
			),
			'*',
		);
        return $sort;
    }

    public function checkEmail($attribute, $params)
	{
		if(!$this->hasErrors())
		{
			$row = Yii::app()->db->createCommand('SELECT * FROM `'.Yii::app()->params['tables']['users'].'` WHERE `email` = \''.$this->email.'\';')->queryRow();
			if (($row['email'] == $this->email) && ($row['id'] != $this->id))
			{
				$this->addError('email', Yii::t('main', 'User with this email is already registered'));
				return false;
			}
		}
	}
	
	public function beforeSave()
	{
		if (Yii::app()->user->checkAccess('admin'))
		{
			if ($this->role == 'organizer' && ($this->organizerName == ''))
			{
				$this->addError('organizerName', Yii::t('main', 'Organizer not by empty'));
				return false;
			}
            if ($this->authAssignment->itemname != 'admin')
            {
                $auth = Yii::app()->authManager;
                $auth->revoke($this->authAssignment->itemname, $this->id);
                $auth->assign($this->role, $this->id);
                $auth->save();
            }
		}
		return parent::beforeSave();
	}
	
	public function afterSave()
	{
		if (Yii::app()->user->checkAccess('admin'))
		{
            if ($this->role == 'organizer')
            {
                $this->saveOrganizerDraft();	   
            }
            if ($this->id == Yii::app()->user->getId())
            {
                Yii::app()->session['userName'] = $this->displayName;
            }
		}
		return parent::afterSave();
	}
	
	private function saveOrganizerDraft()
	{
		$organizerDraft = new OrganizersDraft;
        $organizerDraft->isNewRecord = !OrganizersDraft::model()->exists('title=:title', array(':title' => $this->organizerName));
		$organizerDraft->userId = $this->id;
		$organizerDraft->title = $this->organizerName;
		$organizerDraft->state = 'draft';
		$organizerDraft->updated = date('Y-m-d H:i:s');
		$organizerDraft->save();
	}
}