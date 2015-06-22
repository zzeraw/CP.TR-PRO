<?php
/**
 * Properties:
 * @property string $id
 * @property string $authorId
 * @property string $placeId
 * @property string $organizerId
 * @property string $title
 * @property string $description
 * @property string $eventDate
 *
 * @property string $authorName
 * @property string $placeName
 * @property string $organizerName
 * @property string $categoryName
 *
 * Relations:
 * @property EventsCategories[] $eventsCategories
 * @property Users $users
 * @property OrganizersPublish $organizersPublish
 * @property EventsDraft $eventsDraft
 * @property PlacesPublish $placesPublish
 */
class EventsPublish extends CActiveRecord
{
	public $authorName;
	public $placeName;
	public $organizerName;
	public $categories;
	
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return Yii::app()->params['tables']['eventsPublish'];
	}

	public function rules()
	{
		return array(
			array('authorId, placeId, organizerId', 'length', 'max' => 10),
			array('title', 'length', 'max' => 255),
			array('description, eventDate, numberPeople', 'safe'),
			array('authorName, placeName, organizerName, title, eventDate', 'safe', 'on' => 'search'),
		);
	}

	public function relations()
	{
		return array(
			'users' => array(self::BELONGS_TO, 'Users', 'authorId'),
			'organizersPublish' => array(self::BELONGS_TO, 'OrganizersPublish', 'organizerId'),
			'placesPublish' => array(self::BELONGS_TO, 'PlacesPublish', 'placeId'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'authorName' => Yii::t('main', 'Author name'),
			'placeName' => Yii::t('main', 'Place name'),
			'organizerName' => Yii::t('main', 'Organizer name'),
			'categories' => Yii::t('main', 'Categories'),
			'title' => Yii::t('main', 'Title'),
			'numberPeople' => Yii::t('main', 'Number people'),
			'description' => Yii::t('main', 'Description'),
			'eventDate' => Yii::t('main', 'Event date'),
			'ticketPrice' => Yii::t('main', 'Ticket price'),
			'ticketSales' => Yii::t('main', 'Ticket sales'),
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->with = $this->getRelations();
		$criteria->compare('users.displayName', $this->authorName, true);
		$criteria->compare('placesPublish.title', $this->placeName, true);
		$criteria->compare('organizersPublish.title', $this->organizerName, true);
		$criteria->compare('t.title', $this->title, true);
		$criteria->compare('eventDate', Helper::unformatDate($this->eventDate), true);
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
			'organizersPublish' => array(
				'select' => array('title'),
			),
			'placesPublish' => array(
				'select' => array('title'),
			),
		);
    }
    
    private function getSort()
    {
        $sort = new CSort();
		$sort->attributes = array(
			'authorName' => array(
				'asc' => 'users.displayName ASC',
				'desc' => 'users.displayName DESC',
			),
			'organizerName' => array(
				'asc' => 'organizersPublish.title ASC',
				'desc' => 'organizersPublish.title DESC',
			),
			'placeName' => array(
				'asc' => 'placesPublish.title ASC',
				'desc' => 'placesPublish.title DESC',
			),
			'*',
		);
        return $sort;
    }

    public function beforeDelete()
	{
		EventsPublishCategories::model()->deleteAll(
			'eventId=:eventId', array(':eventId' => $this->id)
		);
		return parent::beforeDelete();
	}
	
	public function getCategories()
	{
		$sql = sprintf('SELECT `title` FROM `%s`, `%s` WHERE `categoryId` = `id` AND `eventId` = \'%s\'',
			Yii::app()->params['tables']['eventsPublishCategories'], 
			Yii::app()->params['tables']['categories'],
			$this->id
		);
		$result = Yii::app()->db->createCommand($sql)->queryAll();
		$categoriesNames = array();
		foreach ($result as $row)
		{
			array_push($categoriesNames, $row['title']);
		}
		return implode(', ', $categoriesNames);
	}
        
    public function getOrganizer()
    {
        $organizer = OrganizersPublish::model()->find(array(
            'select' => 'title',
            'condition' => 'id=:id',
            'params' => array(':id' => $this->organizerId),
        ));
        return $organizer['title'];
    }

    public function getOrganizerLink()
    {
        $title = $this->getOrganizer();
        return CHtml::link($title, '../organizersPublish/view/id/'.$this->organizerId);
    }
}