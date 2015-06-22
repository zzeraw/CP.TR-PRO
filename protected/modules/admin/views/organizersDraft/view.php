<?php
/* @var $this OrganizersDraftController */
/* @var $model OrganizersDraft */
?>

<h1><?php echo Yii::t('main', 'View organizer draft'); ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data' => $model,
	'attributes' => array(
		'title',
		array(
			'name' => 'userName',
			'value' => $model->getUserLink(),
			'type' => 'raw'
		),
		'contacts',
		array(
			'name' => 'state',
			'value' => Yii::t('main', ucfirst($model->state)),
		),
	),
)); ?>
