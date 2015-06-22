<?php
/**
 * Properties:
 * @property string $id
 * @property string $placeId
 * @property string $organizerId
 * @property string $authorId
 * @property string $title
 * @property integer $numberPeople
 * @property string $description
 * @property string $eventDate
 * @property string $state
 * @property string $updated
 * @property integer $ticketPrice
 * @property integer $ticketSales
 * 
 * @property string $placeName
 * @property string $organizerName
 * @property string $authorName
 * @property string $categories
 * @property string $redirectUrl
 * @property string $formProcessing
 *
 * Relations:
 * @property OrganizersDraft $organizersDraft
 * @property OrganizersPublish $organizersPublish
 * @property PlacesDraft $placesDraft
 * @property PlacesPublish $placesPublish
 * @property Users $users
 * @property EventsDraftCategories[] $eventsDraftCategories
 * @property EventsPublish $eventsPublish
 */
class EventsDraft extends CActiveRecord
{
	public $placeName;
	public $organizerName;
	public $authorName;
	public $categories;
	public $formProcessing = false;
	public $redirectUrl;
	private $categoriesIds;
	
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return Yii::app()->params['tables']['eventsDraft'];
	}

	public function rules()
	{
		return array(
			array('placeName, organizerName, categories, title, eventDate', 'required'),
			array('title', 'length', 'max' => 255, 'min' => 3, 
				'tooShort' => Yii::t('main', 'The minimum length of title 3 characters'),
				'tooLong' => Yii::t('main', 'The maximum length of title 255 characters')
			),
			array('eventDate', 'match', 
				'pattern' => '/^[0-9]{2}\/[0-9]{2}\/[0-9]{4} [0-9]{2}:[0-9]{2}$/', 
				'message' => Yii::t('main', 'Event date is invalid')
			),
			array('numberPeople', 'numerical', 'min' => 0, 
				'tooSmall' => Yii::t('main', 'The number of participants is less than zero.'),
				'message' => Yii::t('main', 'People maximum number must be a number')
			),
			array('ticketSales', 'boolean', 'trueValue' => '1', 'falseValue' => '0'),
			array('placeName, authorName, organizerName, categories, description, numberPeople, updated, state, ticketPrice, ticketSales', 'safe'),
			array('id, placeId, organizerId, authorId, title, numberPeople, description, eventDate, state, updated, ticketPrice, ticketSales', 'safe', 'on' => 'search'),

		);
	}

	public function relations()
	{
		return array(
			'organizersDraft' => array(self::BELONGS_TO, 'OrganizersDraft', 'organizerId'),
			'organizersPublish' => array(self::BELONGS_TO, 'OrganizersPublish', 'organizerId'),
			'placesDraft' => array(self::BELONGS_TO, 'PlacesDraft', 'placeId'),
			'placesPublish' => array(self::BELONGS_TO, 'PlacesPublish', 'placeId'),
			'users' => array(self::BELONGS_TO, 'Users', 'authorId'),
			'eventsDraftCategories' => array(self::HAS_MANY, 'EventsDraftCategories', 'eventId'),
			'eventsPublish' => array(self::HAS_ONE, 'EventsPublish', 'id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'placeName' => Yii::t('main', 'Place name'),
			'organizerName' => Yii::t('main', 'Organizer name'),
			'categories' => Yii::t('main', 'Categories'),
			'authorName' => Yii::t('main', 'Author name'),
			'title' => Yii::t('main', 'Title'),
			'numberPeople' => Yii::t('main', 'Number people'),
			'description' => Yii::t('main', 'Description'),
			'eventDate' => Yii::t('main', 'Event date'),
			'state' => Yii::t('main', 'State'),
			'updated' => Yii::t('main', 'Updated'),
			'ticketPrice' => Yii::t('main', 'Ticket price'),
			'ticketSales' => Yii::t('main', 'Ticket sales'),
		);
	}
	
	public function beforeSave()
	{
		if ($this->formProcessing)
		{
			if (!$this->convertAttributes())
			{
				return false;
			}
			$this->eventDate = Helper::unformatDateWithTime($this->eventDate);
			$this->setDefaultRedirect();
			if ($this->placeId == null)
			{
				$this->placeId = $this->savePlaceDraft();
				$this->setRedirectToPlaceDraft();
			}
			if ($this->organizerId == null)
			{
				$this->organizerId = $this->saveOrganizerDraft();
			}
		}
		$this->updated = date('Y-m-d H:i:s');
		return parent::beforeSave();
	}
	
	public function afterSave()
	{
		if ($this->formProcessing)
		{
			$this->saveDraftCategories();
			if ($this->state == 'approved')
			{
				$eventId = $this->saveEventsPublish();
				$this->savePublishCategories($eventId);
				$this->generationTickets($eventId, $this->numberPeople);
				$this->setRedirectToEventPublish($eventId);
			}
		}
		return parent::afterSave();
	}
	
	public function getOrganizerNameByUserId($userId)
	{
		$organzier = OrganizersPublish::model()->find(array(
			'select' => 'title',
			'condition' => 'userId=:userId',
            'params' => array(':userId' => $userId),
		));
		if ($organzier != null)
		{
			return $organzier['title'];
		}
		$organzier = OrganizersDraft::model()->find(array(
			'select' => 'title',
			'condition' => 'userId=:userId',
            'params' => array(':userId' => $userId),
		));
		if ($organzier != null)
		{
			return $organzier['title'];
		}
		return null;
	}
	
	public function getPlaceNameLink($id)
	{
		$placeName = $this->getPlaceName($id, $state);
		$url = ($state == 'draft') ? '/admin/placesDraft/view/id/'.$id : '/admin/placesPublish/view/id/'.$id; 
		return CHtml::link($placeName, $url);
	}
	
	public function getOrganizerNameLink($id)
	{
		$organizerName = $this->getOrganizerName($id, $state);
		$url = ($state == 'draft') ? '/admin/organizersDraft/view/id/'.$id : '/admin/organizersPublish/view/id/'.$id; 
		return CHtml::link($organizerName, $url);
	}
	
	public function getPlaceName($id, &$state = null)
	{
		$place = PlacesPublish::model()->find(array(
			'select' => 'title',
			'condition' => 'id=:id',
			'params' => array(':id' => $id),
		));
		if ($place != null)
		{
			$state = 'approved';
			return $place['title'];
		}
		$place = PlacesDraft::model()->find(array(
			'select' => 'title',
			'condition' => 'id=:id',
			'params' => array(':id' => $id),
		));
		if ($place != null)
		{
			$state = 'draft';
			return $place['title'];
		}
	}
	
	public function getOrganizerName($id, &$state = null)
	{
		$organizer = OrganizersPublish::model()->find(array(
			'select' => 'title',
			'condition' => 'id=:id',
			'params' => array(':id' => $id),
		));
		if ($organizer != null)
		{
			$state = 'approved';
			return $organizer['title'];
		}
		$organizer = OrganizersDraft::model()->find(array(
			'select' => 'title',
			'condition' => 'id=:id',
			'params' => array(':id' => $id),
		));
		if ($organizer != null)
		{
			$state = 'draft';
			return $organizer['title'];
		}
	}
	
	public function getCategories()
	{
		$sql = sprintf('SELECT `title` FROM `%s`, `%s` WHERE `categoryId` = `id` AND `eventId` = \'%s\'',
			Yii::app()->params['tables']['eventsDraftCategories'], 
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
	
	public function searchEditor()
	{
        $criteria = new CDbCriteria;
        $criteria->condition = 't.authorId = '.Yii::app()->user->getId().' AND t.state != \'removed\'';
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
		$criteria->compare('placesPublish.title', $this->placeName, true);
		$criteria->compare('organizersDraft.title', $this->organizerName, true);
		$criteria->compare('users.displayName', $this->authorName, true);
		$criteria->compare('t.title', $this->title, true);
		$criteria->compare('eventDate', Helper::unformatDate($this->eventDate), true);
		$criteria->compare('t.state', $this->state, true);
		$criteria->compare('updated', Helper::unformatDate($this->updated), true);
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => $this->getSort(),
			'pagination' => array(
				'pageSize'  => Yii::app()->params['pagination']['pageSize'],
			),
		));
	}
	
	private function getRelations()
	{
		return array(
			'organizersDraft' => array(
				'select' => array('title'),
			),
			'organizersPublish' => array(
				'select' => array('title'),
			),
			'placesDraft' => array(
				'select' => array('title'),
			),
			'placesPublish' => array(
				'select' => array('title'),
			),
			'users' => array(
				'select' => array('displayName'),
			),
		);
	}
	
	private function getSort()
	{
		$sort = new CSort();
		$sort->attributes = array(
			'authorName' => array(
				'asc' => 'users.displayName',
				'desc' => 'users.displayName DESC',
			),
			'organizerName' => array(
				'asc' => 'organizersDraft.title, organizersPublish.title',
				'desc' => 'organizersDraft.title DESC, organizersPublish.title DESC',
			),
			'placeName' => array(
				'asc' => 'placesDraft.title, placesPublish.title',
				'desc' => 'placesDraft.title DESC, placesPublish.title DESC',
			),
			'*',
		);
		return $sort;
	}
	
	private function convertAttributes()
	{
		if ($this->isNewRecord)
		{
			$this->authorId = Yii::app()->user->getId();
		}
		$this->state = $this->parseButtonValue($this->state);
		$this->placeId = $this->getPlaceId($this->placeName, $placeState);
		if (($this->state != 'draft') && (($this->placeId == null) || ($placeState == 'draft')))
		{
			$this->addError('placeName', Yii::t('main', 'Place not approved'));
		}
		$this->organizerId = $this->getOrganizerId($this->organizerName, $organizerState);
		if (($this->state != 'draft') && (($this->organizerId == null) || ($organizerState == 'draft')))
		{
			$this->addError('organizerName', Yii::t('main', 'Organizer not approved'));
		}
		$this->categoriesIds = $this->getCategoriesIds($this->categories);
		return !$this->hasErrors();
	}
	
	private function getPlaceId($name, &$state = null)
	{
		$place = PlacesPublish::model()->find(array(
			'select' => 'id',
			'condition' => 'title=:title',
			'params' => array(':title' => $name),
		));
		if ($place != null)
		{
			$state = 'approved';
			return $place['id'];
		}
		$place = PlacesDraft::model()->find(array(
			'select' => 'id', 
			'condition' => 'title=:title',
			'params' => array(':title' => $name),
		));
		if ($place != null)
		{
			$state = 'draft';
			return $place['id'];
		}
		return null;
	}
	
	private function getOrganizerId($name, &$state = null)
	{
		$organizer = OrganizersPublish::model()->find(array(
			'select' => 'id', 
			'condition' => 'title=:title',
			'params' => array(':title' => $name),
		));
		if ($organizer != null)
		{
			$state = 'approved';
			return $organizer['id'];
		}
		$organizer = OrganizersDraft::model()->find(array(
			'select' => 'id', 
			'condition' => 'title=:title',
			'params' => array(':title' => $name),
		));
		if ($organizer != null)
		{
			$state = 'draft';
			return $organizer['id'];
		}
		return null;
	}
	
	private function getCategoriesIds($categories)
	{
		$categoriesNames = $this->parseCategories($categories);
		$categoriesIds = array();
		foreach ($categoriesNames as $categoryName)
		{
			$categoryId = $this->getCategoryId($categoryName);
			if ($categoryId == null)
			{
				$this->addError('categories', 
					Yii::t('main', 'Category \'{category}\' not found', array('{category}' => $categoryName))
				);
				break;
			}
			array_push($categoriesIds, $categoryId);
		}
		return $categoriesIds;
	}
	
	private function getCategoryId($categoryName)
	{
		$category = Categories::model()->find(array(
			'select' => 'id', 
			'condition' => 'title=:title',
			'params' => array(':title' => $categoryName),
		));
		return $category['id'];
	}
	
	private function parseCategories($categories)
	{
		$categories = trim($categories);
		if ($categories[strlen($categories) - 1] != ',')
		{
			$categories = $categories.',';
		}
		$categoriesNames = explode(',', $categories);
		array_pop($categoriesNames);
		return array_map('trim', $categoriesNames);
	}
	
	private function savePlaceDraft()
	{
		$placeDraft = new PlacesDraft;
		$placeDraft->authorId = $this->authorId;
		$placeDraft->title = $this->placeName;
		$placeDraft->state = 'draft';
		$placeDraft->save();
		return $placeDraft->id;
	}
	
	private function saveEventsPublish()
	{
		$eventPublish = new EventsPublish();
        $eventPublish->isNewRecord = !EventsPublish::model()->exists('id='.$this->id);
		$eventPublish->id = $this->id;
		$eventPublish->authorId = $this->authorId;
		$eventPublish->placeId = $this->placeId;
		$eventPublish->organizerId = $this->organizerId;
		$eventPublish->title = $this->title;
		$eventPublish->numberPeople = $this->numberPeople;
		$eventPublish->description = $this->description;
		$eventPublish->eventDate = $this->eventDate;
		$eventPublish->ticketPrice = $this->ticketPrice;
		$eventPublish->ticketSales = $this->ticketSales;
		$eventPublish->save();
		return $eventPublish->id;
	}
	
	private function saveOrganizerDraft()
	{
		$organizer = new OrganizersDraft;
		$organizer->title = $this->organizerName;
		$organizer->save();
		return $organizer->id;
	}
	
	private function parseButtonValue($value)
	{
		switch ($value)
		{
			case 'Save draft':
			case 'Update draft':
				return 'draft';
			case 'Publish event':
				return 'approved';
			case 'Send to moderate':
				return 'moderation';
		}
	}
	
	private function saveDraftCategories()
	{
		EventsDraftCategories::model()->deleteAll('eventId='.$this->id);
		foreach ($this->categoriesIds as $categoryId)
		{
			$eventsDraftCategories = new EventsDraftCategories;
			$eventsDraftCategories->eventId = $this->id;
			$eventsDraftCategories->categoryId = $categoryId;
			$eventsDraftCategories->save();
		}
	}
	
	private function savePublishCategories($eventId)
	{
		EventsPublishCategories::model()->deleteAll('eventId='.$eventId);
		foreach ($this->categoriesIds as $categoryId)
		{
			$eventsPublishCategories = new EventsPublishCategories;
			$eventsPublishCategories->eventId = $eventId;
			$eventsPublishCategories->categoryId = $categoryId;
			$eventsPublishCategories->save();
		}
	}
	
	private function setDefaultRedirect()
	{
		if (Yii::app()->user->checkAccess('moderator'))
		{
			$this->redirectUrl = '/admin/eventsDraft/admin';
		}
		else
		{
			$this->redirectUrl = '/admin/dashboard';
		}
	}
	
	private function setRedirectToPlaceDraft()
	{
		$this->redirectUrl = '/admin/placesDraft/update/id/'.$this->placeId;
	}
	
	private function setRedirectToEventPublish($eventId)
	{
		$this->redirectUrl = '/admin/eventsPublish/view/id/'.$eventId;
	}
	
	private function generationTickets($id, $numberPeople)
	{
		$ticketsAmount = 0;
		while ($ticketsAmount < $numberPeople)
		{
			$code = rand(10, 99).'-'.rand(10, 99).'-'.rand(10, 99).'-'.rand(10, 99).'-'.rand(10, 99);
			$exists = Tickets::model()->exists('ticket='.$code);
			if ($exists)
			{
				continue;
			}
			$ticket = new Tickets();
			$ticket->eventId = $id;
			$ticket->ticket = $code;
			$ticket->buy = 0;
			$ticket->save();
			++$ticketsAmount;
		}
	}
}