<?php
class AdminModule extends CWebModule
{
	public function init()
	{
		$this->setImport(array(
			'admin.models.*',
			'admin.components.*',
			'admin.helpers.*',
		));
		Yii::app()->setComponents(array(
			'messages' => array(
				'class' => 'CPhpMessageSource',
				'basePath' => 'protected/modules/admin/messages',
			)
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function getRoles()
	{
		$roles = Yii::app()->authManager->getRoles();
		$arr = array();
		foreach ($roles as $role)
		{
			$arr[$role->name] = ucfirst($role->name);
		}
		return $arr;
	}
}
