<?php
/**
 * Properties:
 * @property string $eventId
 * @property string $ticket
 * @property integer $buy
 *
 * Relations:
 * @property EventsPublish $eventsPublish
 */
class Tickets extends CActiveRecord
{
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
    
	public function tableName()
	{
		return Yii::app()->params['tables']['tickets'];
	}

	public function rules()
	{
		return array(
			array('eventId', 'required'),
			array('buy', 'numerical', 'integerOnly'=>true),
			array('eventId, ticket', 'length', 'max'=>16),
			array('eventId, ticket, buy', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'eventsPublish' => array(self::BELONGS_TO, 'EventsPublish', 'eventId'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'eventId' => 'Event',
			'ticket' => Yii::t('main', 'Ticket'),
			'buy' => 'Buy',
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('eventId',$this->eventId,true);
		$criteria->compare('ticket',$this->ticket,true);
		$criteria->compare('buy',$this->buy);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}