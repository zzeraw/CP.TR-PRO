<?php
class UserIdentity extends CUserIdentity
{
	const ERROR_USER_BLOCKED = 3;
	const ERROR_USER_INACTIVE = 4;
    private $id;
	
    public function authenticate()
    {
        $record = User::model()->findByAttributes(array('email' => $this->username));
        if($record === null)
		{
            $this->errorCode = self::ERROR_USERNAME_INVALID;
		}
		else if($record->password !== crypt($this->password, $record->password))
		{
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
		}
		else if ($record->state == 'blocked')
		{
			$this->errorCode = self::ERROR_USER_BLOCKED;
		}
		else if ($record->state == 'inactive')
		{
			$this->errorCode = self::ERROR_USER_INACTIVE;
		}
        else
        {
            $this->id = $record->id;
            $this->errorCode = self::ERROR_NONE;
			Yii::app()->session['userName'] = $record->displayName;
        }
        return !$this->errorCode;
    }
	
	public function getUserName()
	{
		return $this->userName;
	}

    public function getId()
    {
        return $this->id;
    }
}