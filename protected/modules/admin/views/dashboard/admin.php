<?php
/* @var $this DashboardController */

$this->pageTitle = Yii::t('main', 'Statistics');

?>

<h1><?php echo Yii::t('main', 'Statistics'); ?></h1>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'dataProvider' => $statisticsModel,
    'template' => '{items}',
    'columns'=>array(
        array(
			'header' => Yii::t('main', 'Title'),
			'name' => 'title',
		),
		array(
			'header' => Yii::t('main', 'Statistics'),
			'name' => 'value',
		)
    ),
)); ?>

<h1><?php echo Yii::t('main', 'Inactive users'); ?></h1>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'selectionChanged' => 'function(id) { location.href = \'/admin/users/update/id/\' + $.fn.yiiGridView.getSelection(id); }',
	'htmlOptions' => array('style' => 'cursor: pointer;'),
    'dataProvider' => $usersModel->inactiveSearch(),
	'filter' => $usersModel,
    'columns' => array(
		'email',
		'displayName',
		'socialNetwork',
		'socialUserId',
	)
)); ?>
