<?php

/**
 * This is the model class for table "EventsPublish".
 *
 * The followings are the available columns in table 'EventsPublish':
 * @property string $id
 * @property string $authorId
 * @property string $placeId
 * @property string $organizerId
 * @property string $eventId
 * @property string $categoryId
 * @property string $title
 * @property string $description
 * @property string $eventDate
 *
 * The followings are the available model relations:
 * @property Categories $category
 * @property EventsDraft $event
 * @property Organizers $organizer
 * @property PlacesPublish $place
 * @property Users $author
 */
class EventsPublish extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return EventsPublish the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'EventsPublish';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('authorId, placeId, organizerId, eventId, categoryId', 'required'),
			array('authorId, placeId, organizerId, eventId, categoryId', 'length', 'max'=>10),
			array('title', 'length', 'max'=>255),
			array('description, eventDate', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, authorId, placeId, organizerId, eventId, categoryId, title, description, eventDate', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'category' => array(self::BELONGS_TO, 'Categories', 'categoryId'),
			'event' => array(self::BELONGS_TO, 'EventsDraft', 'eventId'),
			'organizer' => array(self::BELONGS_TO, 'Organizers', 'organizerId'),
			'place' => array(self::BELONGS_TO, 'PlacesPublish', 'placeId'),
			'author' => array(self::BELONGS_TO, 'Users', 'authorId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'authorId' => 'Author',
			'placeId' => 'Place',
			'organizerId' => 'Organizer',
			'eventId' => 'Event',
			'categoryId' => 'Category',
			'title' => 'Title',
			'description' => 'Description',
			'eventDate' => 'Event Date',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('authorId',$this->authorId,true);
		$criteria->compare('placeId',$this->placeId,true);
		$criteria->compare('organizerId',$this->organizerId,true);
		$criteria->compare('eventId',$this->eventId,true);
		$criteria->compare('categoryId',$this->categoryId,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('eventDate',$this->eventDate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}