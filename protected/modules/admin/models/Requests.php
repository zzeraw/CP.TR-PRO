<?php
require_once(Yii::app()->basePath . '/extensions/mail/class.phpmailer.php');

/**
 * Properties:
 * @property string $email
 * @property string $code
 * @property string $role
 */
class Requests extends CActiveRecord
{
	public static function model($className = __CLASS__)
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
			array('email, role', 'required'),
			array('email', 'email', 'message' => Yii::t('main', 'Wrong email')),
			array('email', 'length', 'max' => 255),
			array('email', 'checkEmail'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'email' => Yii::t('main', 'Email'),
			'code' => Yii::t('main', 'Code'),
			'role' => Yii::t('main', 'Role'),
		);
	}
	
	public function checkEmail($attribute, $params)
	{
		if(!$this->hasErrors())
		{
			$check = Yii::app()->db->createCommand('SELECT count(*) FROM `'.Yii::app()->params['tables']['users'].'` WHERE `email` = \''.$this->email.'\';')->queryScalar();
			if ($check)
			{
				$this->addError('email', Yii::t('main', 'User with this email is already registered'));
			}
		}
	}
	
	public function beforeSave()
	{
		$this->code = $this->getCode();
		return parent::beforeSave();
	}
	
	public function afterSave()
	{
		$this->sendMail();
		return parent::afterSave();
	}
	
	private function getCode()
	{
		mt_srand((double)microtime() * 10000);
		$charid = strtoupper(md5(uniqid(rand(), true)));
		$hyphen = chr(45); // "-"
		$code = substr($charid, 0, 8).$hyphen
			.substr($charid, 8, 4).$hyphen
			.substr($charid, 12, 4).$hyphen
			.substr($charid, 16, 4).$hyphen
			.substr($charid, 20, 12);
		return $code;
	}
	
	public function sendMail()
	{
		$link = Yii::app()->request->getBaseUrl(true) . "/site/register?code=" . $this->code;
		$msg = "<p>Вам пришло приглашение в ".Yii::app()->name."</p><p><a href=".$link.">Перейти к регистрации</a></p>";
		try
		{
			$mail = new PHPMailer(true);
			$mail->IsSMTP();
			$mail->SMTPAuth   = true;                  // enable SMTP authentication
			$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
			$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
			$mail->Port       = 465;                   // set the SMTP port for the GMAIL server
			$mail->Username   = Yii::app()->params['email']['username']; // GMAIL username
			$mail->Password   = Yii::app()->params['email']['password']; // GMAIL password
			$mail->CharSet = 'utf-8';
			$mail->AddAddress($this->email, '');
			$mail->SetFrom(Yii::app()->params['email']['username'], 'omega-r.com');
			$mail->AddReplyTo(Yii::app()->params['email']['username'], 'omega-r.com');
			$mail->Subject = 'Приглашение в '.Yii::app()->name;
			$mail->MsgHTML($msg);
			$mail->Send();
		} 
		catch (phpmailerException $e) 
		{
			echo $e->errorMessage();
		} 
		catch (Exception $e) 
		{
			echo $e->getMessage();
		}
	}
}