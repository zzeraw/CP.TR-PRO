<?php
class Helper
{	
	public static function getUserStates()
	{
		$userStates = array(
			'inactive' => Yii::t('main', 'Inactive'),
			'active' => Yii::t('main', 'Active'), 
			'blocked' => Yii::t('main', 'Blocked'),
		);
		return $userStates;
	}
	
	public static function getUserStatesWithEmpty()
	{
		$userStates = array(
			'' => '',
			'inactive' => Yii::t('main', 'Inactive'),
			'active' => Yii::t('main', 'Active'), 
			'blocked' => Yii::t('main', 'Blocked'),
		);
		return $userStates;
	}
	
	public static function getRoles()
	{
		$roles = Yii::app()->authManager->getRoles();
		$list = array();
		foreach ($roles as $role)
		{
                        if ($role->name == 'user')
                        {
                            continue;
                        }
			$list[$role->name] = Yii::t('main', ucfirst($role->name));
		}
		return $list;
	}
	
	public static function getRolesWithEmpty()
	{
		return array('' => '') + self::getRoles();
	}
	
	public static function getPlaceStates()
	{
		$placeStates = array(
			'draft' => Yii::t('main', 'Draft'),
			'moderation' => Yii::t('main', 'Moderation'),
			'approved' => Yii::t('main', 'Approved')
		);
		return $placeStates;
	}
	
	public static function getPlaceStatesWithEmpty()
	{
		$placeStates = array(
			'' => '',
			'draft' => Yii::t('main', 'Draft'),
			'moderation' => Yii::t('main', 'Moderation'),
			'approved' => Yii::t('main', 'Approved')
		);
		return $placeStates;
	}
	
	public static function getEventStates()
	{
		$eventStates = array(
			'draft' => Yii::t('main', 'Draft'),
			'moderation' => Yii::t('main', 'Moderation'),
			'approved' => Yii::t('main', 'Approved')
		);
		return $eventStates;
	}
	
	public static function getEventStatesWithEmpty()
	{
		$eventStates = array(
			'' => '',
			'draft' => Yii::t('main', 'Draft'),
			'moderation' => Yii::t('main', 'Moderation'),
			'approved' => Yii::t('main', 'Approved')
		);
		return $eventStates;
	}
	
	public static function formatDate($date)
	{
		return date('d/m/Y H:i', strtotime($date));
	}
	
	public static function unformatDateWithTime($str)
	{
		if ($str == null)
		{
			return null;
		}
		$date = preg_split('/[-|:|\s\/]/', $str);
		return date('Y-m-d H:i:s', mktime($date[3], $date[4], 0, $date[1], $date[0], $date[2]));
	}
	
	public static function unformatFormDateWithTime($str)
	{
		if ($str == null)
		{
			return null;
		}
		$date = preg_split('/[-|:|\s\/]/', $str);
		return date('Y-m-d H:i', mktime($date[3], $date[4], 0, $date[1], $date[0], $date[2]));
	}
	
	public static function unformatDate($str)
	{
		if ($str == null)
		{
			return null;
		}
		$date = preg_split('/[\/]+/', $str);
		return date('Y-m-d', mktime(0, 0, 0, $date[1], $date[0], $date[2]));
	}
	
	public static function getDashboardPageName()
	{
		$roles = array(
			'admin' => 'Admin',
			'moderator' => 'Moderator',
			'editor' => 'Editor',
			'organizer' => 'Organizer',
		);
		foreach ($roles as $key => $value)
		{
			if (Yii::app()->user->checkAccess($key))
			{
				return $value;
			}
		}
	}
	
	public static function editorModerationState($state, $id)
	{
		if ($state == 'draft')
		{
			return '<a href=\'EventsDraft/sendForModeration/id/'.$id.'\'>'.Yii::t('main', 'Send to moderation').'</a>';
		}
		return Yii::t('main', ucfirst($state));
	}
    
    public static function getState($state)
    {
        switch ($state)
        {
            case 'draft':
                $class = 'label';
                break;
            case 'moderation':
                $class = 'label label-warning';
                break;
            case 'approved':
                $class = 'label label-success';
                break;
        }
        return '<span class="'.$class.'">'.Yii::t('main', ucfirst($state)).'</span>';
    }
}
?>