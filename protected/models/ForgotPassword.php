<?php
require_once(Yii::app()->basePath . '/extensions/mail/class.phpmailer.php');

class ForgotPassword extends CActiveRecord
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
			array('email', 'required'),
			array('email', 'length', 'max' => 255),
			array('email', 'checkEmail'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'email' => 'Email'
		);
	}
	
	public function checkEmail($attribute, $params)
	{
		if(!$this->hasErrors())
		{
			$check = Yii::app()->db->createCommand('SELECT count(*) FROM `'.Yii::app()->params['tables']['users'].'` WHERE `email` = \''.$this->email.'\';')->queryScalar();
			if (!$check)
			{
				$this->addError('email', Yii::t('main', 'User with this email is not registered.'));
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
		$link = Yii::app()->request->getBaseUrl(true) . "/site/chandepassword?code=" . $this->code;
		$msg = "<p>You link: ".$link."</p>";
		try
		{
			$mail = new PHPMailer(true);
			$mail->IsSMTP();
			$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
			$mail->SMTPAuth   = true;                  // enable SMTP authentication
			$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
			$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
			$mail->Port       = 465;                   // set the SMTP port for the GMAIL server
			$mail->Username   = Yii::app()->params['email']['username']; // GMAIL username
			$mail->Password   = Yii::app()->params['email']['password']; // GMAIL password
			$mail->AddAddress($this->email, '');
			$mail->SetFrom(Yii::app()->params['email']['username'], 'omega-r.com');
			$mail->AddReplyTo(Yii::app()->params['email']['username'], 'omega-r.com');
			$mail->Subject = 'Request to looker';
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