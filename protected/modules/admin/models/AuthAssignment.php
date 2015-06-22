<?php
/**
 * Properties:
 * @property string $itemname
 * @property string $userid
 * @property string $bizrule
 * @property string $data
 *
 * Relations:
 * @property AuthItem $authItem
 */
class AuthAssignment extends CActiveRecord
{
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return Yii::app()->params['tables']['authAssignment'];
	}

	public function rules()
	{
		return array(
			array('itemname, userid', 'required'),
			array('itemname, userid', 'length', 'max' => 64),
			array('bizrule, data', 'safe'),
			array('itemname, userid, bizrule, data', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'authItem' => array(self::BELONGS_TO, 'AuthItem', 'itemname'),
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('itemname', $this->itemname, true);
		$criteria->compare('userid', $this->userid, true);
		$criteria->compare('bizrule', $this->bizrule, true);
		$criteria->compare('data', $this->data, true);
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}