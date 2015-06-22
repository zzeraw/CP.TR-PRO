<?php
class ChangePasswordForm extends CFormModel
{
	public $password;
	public $confirmPassword;

	public function rules()
	{
		return array(
			array('password, confirmPassword', 'required'),
			array('password, confirmPassword', 'length', 'max' => 255),
			array('confirmPassword', 'checkConfirmPassword'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'password' => Yii::t('main', 'Password'),
			'confirmPassword' => Yii::t('main', 'Confirm password')
		);
	}

	public function checkConfirmPassword($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			if ($this->password != $this->confirmPassword)
			{
				$this->addError('confirmPassword', Yii::t('main', 'Passwords not match'));
			}
		}
	}

	public function validateCode($code)
	{
		return Request::model()->count("code=:code", array("code" => $code));
	}
	
	public function change()
	{
		$request = Request::model()->find('code=:code', array(':code' => $_GET['code']));

		$user = User::model()->find('email=:email', array(':email' => $request->email));
		$user->password = crypt($this->password);
		$user->save();

		Request::model()->deleteAll('code=:code', array(':code' => $_GET['code']));

		return true;
	}
}