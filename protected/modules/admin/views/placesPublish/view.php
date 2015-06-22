<?php
/* @var $this PlacesPublishController */
/* @var $model PlacesPublish */

$this->pageTitle = Yii::t('main', 'View place publish');
?>

<h1><?php echo Yii::t('main', 'View place publish'); ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data' => $model,
	'attributes' => array(
		'title',
		'address',
		'latitude',
		'longitude',
		array(
			'label' => Yii::t('main', 'Location'),
			'type' => 'raw',
			'value' => $model->viewMap(),
		),
		'contacts',
		array(
			'name' => 'authorName',
			'value' => $model->users->displayName,
		),
	),
));
?>
