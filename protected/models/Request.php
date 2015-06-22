<?php
class Request extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return Yii::app()->params['tables']['requests'];
	}

	public function rules()
	{
		return array(
			array('email, code, role', 'required'),
			array('email, code', 'length', 'max'=>255),
			array('role', 'length', 'max'=>9),
			array('email, code, role', 'safe', 'on'=>'search'),
		);
	}
}