<?php
/* @var $this OrganizersPublishController */
/* @var $model OrganizersPublish */
?>

<h1><?php echo Yii::t('main', 'View organizers publish') ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data' => $model,
	'attributes' => array(
		'title',
		array(
			'name' => 'userName',
			'value' => $model->getUserLink(),
            'type' => 'raw',
		),
		'contacts',
	),
)); ?>
