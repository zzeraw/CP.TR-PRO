<?php
class InstallController extends Controller
{
	public function filters()
    {
		return array(
			'accessControl',
		);
    }

	public function accessRules()
	{
		return array(
			array('allow',
				'actions' => array('index'),
				'users' => array('*'),
			),
			array('deny',
				'users' => array('*'),
			)
		);
	}

	public function actionIndex()
	{
		$auth = Yii::app()->authManager;

		$role = $auth->createRole('user');

		$role = $auth->createRole('editor');
		$role->addChild('user');

		$role = $auth->createRole('organizer');
		$role->addChild('editor');

		$role = $auth->createRole('moderator');
		$role->addChild('editor');

		$role = $auth->createRole('admin');
		$role->addChild('moderator');

		$sql = 'INSERT INTO `'.Yii::app()->params['tables']['users'].'` (`id`, `displayName`, `email`, `password`, `socialNetwork`, `socialUserId`, `state`) VALUES (\'1\', \'admin\', \'admin@admin.ru\', \'$1$Td3.gm/.$Tyhge6nOSL.85.A.XMvLL/\', NULL, NULL, \'active\');';
		Yii::app()->db->createCommand($sql)->execute();

		$auth->assign('admin', '1');
		echo 'Install OK';
	}
}