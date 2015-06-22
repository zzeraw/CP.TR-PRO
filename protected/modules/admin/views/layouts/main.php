<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	
	<?php Yii::app()->bootstrap->register(); ?>

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/styles.css" />
</head>

<body>
	
<?php
$navItems = array();
if (Yii::app()->user->checkAccess('admin'))
{
	array_push($navItems, array(
		'class' => 'bootstrap.widgets.TbButtonGroup',
		'buttons' => array(
			array('label' => Yii::t('main', 'Home'), 'url' => '/admin/dashboard', 
				'active' => ($this->route == 'admin/dashboard/index')),
		),
	));
}
else if (Yii::app()->user->checkAccess('moderator'))
{
	array_push($navItems, array(
		'class' => 'bootstrap.widgets.TbButtonGroup',
		'buttons' => array(
			array('label' => Yii::t('main', 'For moderation'), 'url' => '/admin/dashboard', 
				'active' => ($this->route == 'admin/dashboard/index')),
		),
	));
}
else if (Yii::app()->user->checkAccess('organizer') || Yii::app()->user->checkAccess('editor'))
{
	array_push($navItems, array(
		'class' => 'bootstrap.widgets.TbButtonGroup',
		'buttons' => array(
			array('label' => Yii::t('main', 'My events'), 'url' => '/admin/dashboard', 
				'active' => ($this->route == 'admin/dashboard/index')),
			array('label' => '+', 'url' => '/admin/eventsDraft/add',
				'active' => ($this->route == 'admin/eventsDraft/add')),
		),
	));
}
if (Yii::app()->user->checkAccess('moderator'))
{
	array_push($navItems, array(
		'class' => 'bootstrap.widgets.TbButtonGroup',
		'buttons' => array(
			array('label' => Yii::t('main', 'Events'), 'url' => '/admin/eventsPublish/admin', 
				'active' => (($this->route == 'admin/eventsPublish/admin') || ($this->route == 'admin/eventsDraft/admin') || ($this->route == 'admin/eventsDraft/removed'))),
			array('label' => '+', 'url' => '/admin/eventsDraft/add',
				'active' => ($this->route == 'admin/eventsDraft/add')),
		),
	));
}
array_push($navItems, array(
	'class' => 'bootstrap.widgets.TbButtonGroup',
	'buttons' => array(
		array('label' => Yii::t('main', 'Places'), 'url' => '/admin/placesPublish/admin', 
			'active' => (($this->route == 'admin/placesPublish/admin') || ($this->route == 'admin/placesDraft/admin') || ($this->route == 'admin/placesDraft/removed'))),
		array('label' => '+', 'url' => '/admin/placesDraft/add',
			'active' => ($this->route == 'admin/placesDraft/add')),
	),
));
if (!Yii::app()->user->checkAccess('organizer'))
{
	array_push($navItems, array(
		'class' => 'bootstrap.widgets.TbButtonGroup',
		'buttons' => array(
			array('label' => Yii::t('main', 'Organizers'), 'url' => '/admin/organizersPublish/admin',
				'active' => ($this->route == 'admin/organizersPublish/admin')),
			array('label' => '+', 'url' => '/admin/organizersDraft/add',
				'active' => ($this->route == 'admin/organizersDraft/add')),
		),
	));
}
if (Yii::app()->user->checkAccess('admin'))
{
	array_push($navItems, array(
		'class' => 'bootstrap.widgets.TbButtonGroup',
		'buttons' => array(
			array('label' => Yii::t('main', 'Categories'), 'url' => '/admin/categories/admin',
				'active' => ($this->route == 'admin/categories/admin')),
			array('label' => '+', 'url' => '/admin/categories/add',
				'active' => ($this->route == 'admin/categories/add'))
		),
	));
	array_push($navItems, array(
		'class' => 'bootstrap.widgets.TbButtonGroup',
		'buttons' => array(
			array('label' => Yii::t('main', 'Users'), 'url' => '/admin/users/admin',
				'active' => ($this->route == 'admin/users/admin')),
			array('label' => '+', 'url' => '/admin/users/add',
				'active' => ($this->route == 'admin/users/add')),
		),
	));
}
array_push($navItems, array(
	'class' => 'bootstrap.widgets.TbMenu',
	'htmlOptions' => array('class' => 'pull-right'),
	'items' => array(
		array('label' => Yii::app()->session['userName'], 
			'url' => '/admin/users/view/id/'.Yii::app()->user->id,
			'linkOptions' => array('style' => 'text-decoration: underline'),
		),
		array('label' => Yii::t('main', 'Logout'), 
			'url' => '/site/logout',
			'linkOptions' => array('style' => 'text-decoration: underline'),
		),
	),
));
$this->widget('bootstrap.widgets.TbNavbar', array(
	'brand' => false,
    'items' => $navItems,
));
?>

<div class="container" id="page">

	<?php echo $content; ?>

	<div class="clear"></div>

</div><!-- page -->

<div class="form-actions">
	<div id="footer">
		Создано <a href="http://omega-r.com/">Omega-R</a>.<br/>
		Copyright &copy; <?php echo date('Y'); ?>.<br/>
		All Rights Reserved.
	</div><!-- footer -->
</div>
</body>
</html>
