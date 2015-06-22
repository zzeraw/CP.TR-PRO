<?php
// bootstrap
Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');

return array(
	'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name' => 'Бизнес молодость',
	'sourceLanguage' => 'en',
	'language' => 'ru',
	'charset' => 'utf-8',
	'preload' => array(
		'log',
	),
	'import' => array(
		'application.models.*',
		'application.components.*',
		'application.extensions.bootstrap.widgets.*',
		'application.extensions.EGMap.*',
		'application.extensions.CJuiDateTimePicker.CJuiDateTimePicker',
	),
	'modules' => array(
		'admin',
		'api',
		'gii' => array(
			'class' => 'system.gii.GiiModule',
			'password' => 'qwerty',
			'generatorPaths' => array('bootstrap.gii'),
		),
	),
	'components' => array(
		'bootstrap' => array(
            'class' => 'bootstrap.components.Bootstrap',
        ),
		'user' => array(
			'allowAutoLogin' => true,
		),
		'urlManager' => array(
			'urlFormat' => 'path',
			'showScriptName' => false,
			'rules' => array(
				'<controller:\w+>/<id:\d+>' => '<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
				'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
				'<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/<controller>/<action>',
			),
		),
		'db' => array(
			'connectionString' => 'mysql:host=localhost;dbname=trainings-pro',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'admin',
			'charset' => 'utf8',
			'enableProfiling' => true,
			'enableParamLogging' => true,
		),
		'authManager' => array(
			'class' => 'CDbAuthManager',
			'connectionID' => 'db',
			'defaultRoles'=>array('authenticated', 'user'),
		),
		'errorHandler' => array(
			'errorAction' => 'site/error',
		),
		'log' => array(
			'class' => 'CLogRouter',
			'routes' => array(
				array(
					'class' => 'CFileLogRoute',
					'levels' => 'trace',
					'categories' => 'test',
				),
				array(
					'class' => 'CWebLogRoute',
					'levels' => 'error, warning, profile, trace, info',
					'categories' => 'dump',
				),
			),
		),
	),
	// using Yii::app()->params['paramName']
	'params' => array(
		'adminEmail' => 'webmaster@example.com',
		'pagination' => array(
			'pageSize' => 30,
		),
		'tables' => array( // Yii::app()->params['tables']['']
			'authAssignment' => 'AuthAssignment',
			'authItem' => 'AuthItem',
			'authItemChild' => 'AuthItemChild',
			'categories' => 'Categories',
			'devices' => 'Devices',
			'eventsDraft' => 'EventsDraft',
			'eventsPublish' => 'EventsPublish',
			'placesDraft' => 'PlacesDraft',
			'placesPublish' => 'PlacesPublish',
			'organizersDraft' => 'OrganizersDraft',
			'organizersPublish' => 'OrganizersPublish',
			'requests' => 'Requests',
			'users' => 'Users',
			'eventsDraftCategories' => 'EventsDraftCategories',
			'eventsPublishCategories' => 'EventsPublishCategories',
			'tickets' => 'Tickets',
		),
		'email' => array(
			'username' => 'trainings.pro.omega@gmail.com',
			'password' => '365748974',
		),
		'api' => array(
			'username' => 'ios-client',
			'password' => '947d8f00-9605-4f61-a854-b51401cf7302',
		),
		'map' => array(
			'size' => array(
				'width' => '400',
				'height' => '340',
			),
			'center' => array(
				'latitude' => '56.140959',
				'longitude' => '47.281036',
			),
			'zoom' => '11',
			'view_zoom' => '15',
		),
	),
);