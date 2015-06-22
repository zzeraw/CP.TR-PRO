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
 * 
 * @property string $authorName
 *
 * Relations:
 * @property Users $users
 * @property PlacesDraft $placesDraft
 */
class PlacesPublish extends CActiveRecord
{
	public $authorName;
	
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return Yii::app()->params['tables']['placesPublish'];
	}

	public function rules()
	{
		return array(
			array('latitude, longitude', 'numerical'),
			array('authorId', 'length', 'max' => 10),
			array('title, address', 'length', 'max' => 255),
			array('contacts', 'safe'),
			array('authorName, title, address', 'safe', 'on' => 'search'),
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
			'contacts' => Yii::t('main', 'Contacts'),
			'latitude' => Yii::t('main', 'Latitude'),
			'longitude' => Yii::t('main', 'Longitude'),
		);
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
    
    public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->with = $this->getRelations();
		$criteria->together = true;
		$criteria->compare('title', $this->title, true);
		$criteria->compare('address', $this->address, true);
		$criteria->compare('users.displayName', $this->authorName, true);
        $criteria->order = 't.id DESC';
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
				'asc' => 'users.displayName',
				'desc' => 'users.displayName DESC',
			),
			'*',
		);
        return $sort;
    }
}