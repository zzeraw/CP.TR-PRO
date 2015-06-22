<?php
class LoginForm extends CFormModel
{
	public $email;
	public $password;
	public $rememberMe;
	private $identity;	
	
	public function rules()
	{
		return array(
			array('email, password', 'required'),
			array('rememberMe', 'boolean'),
			array('password', 'authenticate'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'email' => Yii::t('main', 'Email'),
			'password' => Yii::t('main', 'Password'),
		);
	}

	public function authenticate($attribute, $params)
	{
		if(!$this->hasErrors())
		{
			$this->identity = new UserIdentity($this->email, $this->password);
			if($this->identity->authenticate())
			{
				Yii::app()->user->login($this->identity);
			}
			else
			{
				echo $this->identity->errorMessage;
			}
		}
	}
	
	public function login()
	{
		if ($this->identity === null)
		{
			$this->identity = new UserIdentity($this->email, $this->password);
			$this->identity->authenticate();
		}
		switch ($this->identity->errorCode)
		{
			case UserIdentity::ERROR_USERNAME_INVALID:
			case UserIdentity::ERROR_PASSWORD_INVALID:
			{
				$this->addError('user', Yii::t('main', 'Wrong email or password'));
				return false;
			}
			case UserIdentity::ERROR_USER_BLOCKED:
			{
				$this->addError('user', Yii::t('main', 'You are blocked'));
				return false;
			}
			case UserIdentity::ERROR_USER_INACTIVE:
			{
				$this->addError('user', Yii::t('main', 'Wait for the administrator approves your request'));
				return false;
			}
			default:
			{
				$duration = $this->rememberMe ? (3600 * 24 * 30) : 0; // 30 days
				Yii::app()->user->login($this->identity, $duration);
				return true;
			}
		}
	}
}
