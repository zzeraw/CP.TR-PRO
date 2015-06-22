<?php
class RegisterForm extends CFormModel
{
	public $email;
	public $password;
	public $confirmPassword;
	public $displayName;
	public $roleId;

	public function rules()
	{
		return array(
			array('displayName, email, password, confirmPassword', 'required'),
			array('email', 'email', 'message' => Yii::t('main', 'Wrong email')),
			array('displayName', 'length', 'max' => 255, 'min' => 3, 
				'tooShort' => Yii::t('main', 'The minimum length of 3 characters'),
				'tooLong' => Yii::t('main', 'The maximum length of 255 characters')
			),
			array('email', 'length', 'max' => 255, 'min' => 4, 
				'tooShort' => Yii::t('main', 'The minimum length of email 4 characters'),
				'tooLong' => Yii::t('main', 'The maximum length of email 255 characters')
			),
			array('password', 'length', 'max' => 255, 'min' => 4, 
				'tooShort' => Yii::t('main', 'The minimum length of password 4 characters'),
			),
			array('confirmPassword', 'confirmPassword'),
			array('email', 'checkEmail'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'displayName' => Yii::t('main', 'Display Name'),
			'password' => Yii::t('main', 'Password'),
			'confirmPassword' => Yii::t('main', 'Confirm your password'),
		);
	}
	
	public function confirmPassword($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			if ($this->password != $this->confirmPassword)
			{
				$this->addError('confirmPassword', Yii::t('main', 'Passwords not match'));
			}
		}
	}

	public function checkEmail($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$checkEmail = User::model()->count('email=:email', array(':email' => $this->email));
			if($checkEmail != 0)
			{
				$this->addError('email', Yii::t('main', 'Email already use.'));
				return false;
			}
		}
	}	
	
	public function validateCode($code)
	{
		return Request::model()->count("code=:code", array("code" => $code));
	}

	public function register()
	{
		if ($this->hasErrors())
		{
			return false;
		}
		$request = Request::model()->find('code=:code', array(':code' => $_GET['code']));
		$user = new User;
		$user->email = $this->email;
		$user->displayName = $this->displayName;
		$user->password = crypt($this->password);
		$user->state = 'active';
		$user->save();
		Request::model()->deleteAll('code=:code', array(':code' => $_GET['code']));
		$auth = Yii::app()->authManager;
		$auth->assign($request->role, $user->id);
		$auth->save();
		return true;
	}

	public function registerUser()
	{
		if ($this->hasErrors())
		{
			return false;
		}
		$checkEmail = User::model()->count('email=:email', array(':email' => $this->email));
		if($checkEmail == 0)
		{
			$user = new User;
			$user->email = $this->email;
			$user->displayName = $this->displayName;
			$user->password = crypt($this->password);
			$user->state = 'inactive';
			$user->save();
			$auth = Yii::app()->authManager;
			$auth->assign('editor', $user->id);
			$auth->save();
			return true;
		} 
		else 
		{
			throw new CHttpException(403, Yii::t('main', 'Email already use.'));
		}

		return true;
	}
}
