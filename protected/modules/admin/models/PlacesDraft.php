<?php
/**
 * Properties:
 * @property string $id
 * @property string $authorId
 * @property string $title
 * @property string $address
 * @property double $latitude
 * @property double $longitude
 * @property string $contacts
 * @property string $state
 * @property string $updated
 *
 * @property string $authorName
 * @property string $formProcessing
 * @property string $redirectUrl
 *
 * Relations:
 * @property Users $users
 */
class PlacesDraft extends CActiveRecord
{
	public $authorName;
	public $formProcessing = false;
	public $redirectUrl;

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return Yii::app()->params['tables']['placesDraft'];
	}

	public function rules()
	{
		return array(
			array('title', 'required'),
			array('latitude, longitude', 'numerical', 'message' => Yii::t('main', '{attribute} must be a number')),
			array('title, address', 'length', 
				'max' => 255, 
				'min' => 3, 
				'tooShort' => Yii::t('main', 'The minimum length of title 3 characters'),
				'tooLong' => Yii::t('main', 'The maximum length of title 255 characters')
			),
			array('contacts, updated, state', 'safe'),
			array('authorName, title, address, state, updated', 'safe', 'on' => 'search'),
		);
	}

	public function relations()
	{
		return array(
			'users' => array(self::BELONGS_TO, 'Users', 'authorId'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'authorName' => Yii::t('main', 'Author name'),
			'title' => Yii::t('main', 'Title'),
			'address' => Yii::t('main', 'Address'),
			'state' => Yii::t('main', 'State'),
			'updated' => Yii::t('main', 'Updated'),
			'contacts' => Yii::t('main', 'Contacts'),
			'latitude' => Yii::t('main', 'Latitude'),
			'longitude' => Yii::t('main', 'Longitude'),
		);
	}
	
	public function beforeSave()
	{
		if ($this->formProcessing)
		{
			$this->setDefaultRedirect();
			if ($this->isNewRecord)
			{
				$this->authorId = Yii::app()->user->getId();
			}
			$this->state = $this->parseButtonValue($this->state);
			if (($this->state == 'approved') && ($this->address == null))
			{
				$this->addError('address', Yii::t('main', 'Address cannot be blank'));
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
			$this->savePlacesPublish();
			$this->setRedirectToPlacePublish();
		}
		return parent::afterSave();
	}
    
    public function placeIsPublish()
    {
        return PlacesPublish::model()->exists('id=:id', array(':id' => $this->id));
    }

    public function viewMap()
	{
		$gMap = new EGMap();
		$gMap->setWidth(Yii::app()->params['map']['size']['width']);
		$gMap->setHeight(Yii::app()->params['map']['size']['height']);
		$scripts = array();
		if ($this->latitude && $this->longitude)
		{
			$gMap->zoom = Yii::app()->params['map']['view_zoom'];
			$gMap->setCenter($this->latitude, $this->longitude);
			array_push($scripts, 
				'marker = new google.maps.Marker({
					position: new google.maps.LatLng('.$this->latitude.', '.$this->longitude.'),
					map: '.$gMap->getJsName().', 
					draggable: false, 
				});'
			);
		}
		else
		{
			$gMap->zoom = Yii::app()->params['map']['zoom'];
			$gMap->setCenter(Yii::app()->params['map']['center']['latitude'], Yii::app()->params['map']['center']['longitude']);
		}
		$gMap->appendMapTo('#map');
		$gMap->renderMap($scripts);
		return '<div id="map"></div>';
	}
	
	public function showMap()
	{
		$gMap = new EGMap();
		$gMap->setWidth(Yii::app()->params['map']['size']['width']);
		$gMap->setHeight(Yii::app()->params['map']['size']['height']);
		$gMap->zoom = Yii::app()->params['map']['zoom'];
		$gMap->setCenter(Yii::app()->params['map']['center']['latitude'], Yii::app()->params['map']['center']['longitude']);
		$gMap->addEvent(new EGMapEvent('click', 'function(event) {
			if (marker != undefined)
			{
				marker.setMap(null);
			}
			var lat = event.latLng.lat();
			var lng = event.latLng.lng();
			marker = new google.maps.Marker({
				position: event.latLng, 
				map: '.$gMap->getJsName().', 
				draggable: false, 
			});
			google.maps.event.addListener(marker, "click", function() {
				marker.setMap(null);
				$("#PlacesDraft_latitude").val("");
				$("#PlacesDraft_longitude").val("");
			});
			$("#PlacesDraft_latitude").val(lat);
			$("#PlacesDraft_longitude").val(lng);
			geocoder.geocode(
				{\'latLng\': event.latLng}, 
				function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						var address, result = results[0].address_components;
						if (result[0].types == "street_number")
						{
							address = result[1].short_name + ", " + result[0].short_name;
						}
						else
						{
							address = result[0].short_name;
						}
						$("#PlacesDraft_address").val(address);
						console.log(result[0].types);
						console.log(result);
					}

				}
			);
		}', false, EGMapEvent::TYPE_EVENT_DEFAULT));
		$scripts = array();
		if ($this->latitude && $this->longitude)
		{
			$scripts = array(
				'marker = new google.maps.Marker({
					position: new google.maps.LatLng('.$this->latitude.', '.$this->longitude.'),
					map: '.$gMap->getJsName().', 
					draggable: false, 
				});
				google.maps.event.addListener(marker, "click", function() {
					marker.setMap(null);
					$("#PlacesDraft_latitude").val("");
					$("#PlacesDraft_longitude").val("");
				});'
			);
		}
		$gMap->appendMapTo('#map');
		$gMap->renderMap($scripts);
		return '<div id="map"><script type=\'text/javascript\'>var marker; var geocoder = new google.maps.Geocoder();</script></div>';
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
	
	public function moderatorSearch()
	{
		$criteria = new CDbCriteria;
		$criteria->condition = 't.state = \'moderation\'';
		return $this->searchTemplate($criteria);
	}
    
    private function searchTemplate($criteria)
    {
        $criteria->with = $this->getRelations();
		$criteria->together = true;
        if (!Yii::app()->user->checkAccess('moderator'))
		{
            $criteria->addCondition('authorId = '.Yii::app()->user->getId());
		}
		$criteria->compare('title', $this->title, true);
		$criteria->compare('address', $this->address, true);
		$criteria->compare('t.state', $this->state, true);
		$criteria->compare('updated', Helper::unformatDate($this->updated), true);
		$criteria->compare('users.displayName', $this->authorName, true);
        $criteria->order = 'updated DESC';
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
				'select' => array('id', 'displayName'),
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
			'*',
		);
        return $sort;
    }
	
	private function setDefaultRedirect()
	{
		$this->redirectUrl = '/admin/placesDraft/admin';
	}
	
	private function setRedirectToPlacePublish()
	{
		$this->redirectUrl = '/admin/placesPublish/admin';
	}
	
	private function savePlacesPublish()
	{
		$placesPublish = new PlacesPublish();
        $placesPublish->isNewRecord = !PlacesPublish::model()->exists('id=:id', array(':id' => $this->id));
		$placesPublish->id = $this->id;
		$placesPublish->authorId = $this->authorId;		
		$placesPublish->title = $this->title;
		$placesPublish->address = $this->address;
		$placesPublish->latitude = $this->latitude;
		$placesPublish->longitude = $this->longitude;
		$placesPublish->contacts = $this->contacts;
		$placesPublish->save();
	}

    private function parseButtonValue($value)
	{
		switch ($value)
		{
			case 'Save draft':
			case 'Update draft':
				return 'draft';
			case 'Publish place':
				return 'approved';
			case 'Send to moderate':
				return 'moderation';
		}
	}
}