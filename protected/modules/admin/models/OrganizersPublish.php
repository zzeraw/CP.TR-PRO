<?php
/**
 * Properties:
 * @property string $id
 * @property string $userId
 * @property string $title
 * @property string $contacts
 * 
 * @property string $userName
 *
 * Relations:
 * @property OrganizersPublish $organizersPublish
 * @property Users $users
 */
class OrganizersPublish extends CActiveRecord
{
	public $userName;
	
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return Yii::app()->params['tables']['organizersPublish'];
	}

	public function rules()
	{
		return array(
			array('id, title', 'required'),
			array('id, userId', 'length', 'max' => 10),
			array('title', 'length', 'max' => 255),
			array('contacts', 'safe'),
			array('id, userName, title, contacts', 'safe', 'on' => 'search'),
		);
	}

	public function relations()
	{
		return array(
			'organizersPublish' => array(self::BELONGS_TO, 'OrganizersPublish', 'id'),
			'users' => array(self::BELONGS_TO, 'Users', 'userId'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'userName' => Yii::t('main', 'User name'),
			'title' => Yii::t('main', 'Title'),
			'contacts' => Yii::t('main', 'Contacts'),
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;
        $criteria->with = $this->getRelations();
        $criteria->order = 't.id DESC';
		$criteria->compare('users.displayName', $this->userName, true);
		$criteria->compare('title', $this->title, true);
		$criteria->compare('contacts', $this->contacts, true);
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
				'asc' => 'users.displayName',
				'desc' => 'users.displayName DESC',
			),
			'*',
		);
        return $sort;
    }
	
	public function getUserLink()
	{
		if ($this->userId == null)
		{
			return;
		}
		return '<a href="/admin/users/view/id/'.$this->userId.'">'.$this->users->displayName.'</a>';
	}
}