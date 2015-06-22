<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/styles.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

	<?php Yii::app()->bootstrap->register(); ?>
</head>

<body>

<?php $this->widget('bootstrap.widgets.TbNavbar', array(
    'items' => array(
        array(
            'class' => 'bootstrap.widgets.TbMenu',
            'items' => array(
				array('label' => Yii::t('main', 'Login'), 'url' => array('/site/login'), 'visible' => Yii::app()->user->isGuest),
				array('label' => Yii::t('main', 'Register'), 'url' => array('/site/userRegistration'), 'visible' => Yii::app()->user->isGuest),
            ),
        ),
    ),
)); ?>

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
