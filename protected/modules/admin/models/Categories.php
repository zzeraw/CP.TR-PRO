<?php
/**
 * Properties:
 * @property string $id
 * @property string $title
 *
 * Relations:
 * @property EventsCategories[] $eventsCategories
 */
class Categories extends CActiveRecord
{
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return Yii::app()->params['tables']['categories'];
	}

	public function rules()
	{
		return array(
			array('title', 'required'),
			array('title', 'length', 'max' => 255, 'min' => 3, 
				'tooShort' => Yii::t('main', 'The minimum length of title 3 characters'),
				'tooLong' => Yii::t('main', 'The maximum length of title 255 characters')
			),
			array('title', 'safe', 'on' => 'search'),
		);
	}

	public function relations()
	{
		return array(
			'eventsCategories' => array(self::HAS_MANY, 'EventsCategories', 'categoryId'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'title' => Yii::t('main', 'Title'),
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('title', $this->title, true);
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'pagination' => array(
				'pageSize'  => Yii::app()->params['pagination']['pageSize'],
			),
		));
	}
}