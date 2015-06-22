<?php
/* @var $this UsersController */
/* @var $model Users */

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#users-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");

$this->pageTitle = Yii::t('main', 'Manage users');
?>

<h1><?php echo Yii::t('main', 'Manage users'); ?></h1>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'dataProvider' => $model->search(),
	'filter' => $model,
	'selectionChanged' => 'function(id) { location.href = \'/admin/users/update/id/\' + $.fn.yiiGridView.getSelection(id); }',
	'htmlOptions' => array('style' => 'cursor: pointer;'),
    'columns' => array(
		'email',
		'displayName',
		'socialNetwork',
		'socialUserId',
		array(
			'name' => 'role',
			'value' => 'Yii::t(\'main\', ucfirst($data->authAssignment->itemname))',
			'filter' => Helper::getRoles(),
		),
		array(
			'name' => 'state',
			'value' => 'Yii::t(\'main\', ucfirst($data->state))',
			'filter' => Helper::getUserStates(),
		),
	)
)); ?>
