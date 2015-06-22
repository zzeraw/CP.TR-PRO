<?php
/* @var $this UsersController */
/* @var $model Users */

$this->pageTitle = Yii::t('main', 'Profile');
?>

<h1><?php echo Yii::t('main', 'Profile'); ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data' => $model,
	'attributes' => array(
		'email',
		'displayName',
		'socialNetwork',
		'socialUserId',
		array(
			'label' => Yii::t('main', 'Role'),
			'value' => Yii::t('main', ucfirst($model->authAssignment->itemname)),
		),
		array(
			'name' => 'state',
			'value' => Yii::t('main', ucfirst($model->state)),
		),
		array(
			'value' => CHtml::link(Yii::t('main', 'Edit profile'), '/admin/users/update/id/'.$model->id),
			'type' => 'html',
			'visible' => (Yii::app()->user->checkAccess('admin') || ($model->id == Yii::app()->user->getId())),
		),
	),
)); ?>
