<?php
/* @var $this PlacesDraftController */
/* @var $model PlacesDraft */

$this->pageTitle = Yii::t('main', 'View place draft');
?>

<h1><?php echo Yii::t('main', 'View place draft'); ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data' => $model,
	'attributes' => array(
		'title',
		'address',
		'contacts',
		array(
			'name' => 'state',
			'value' => Yii::t('main', ucfirst($model->state)),
		),
		array(
			'name' => 'updated',
			'value' => Helper::formatDate($model->updated),
		),
		array(
			'label' => Yii::t('main', 'Location'),
			'type' => 'raw',
			'value' => $model->viewMap(),
		),
		array(
			'name' => 'authorName',
			'value' => $model->users->displayName,
		),
	),
)); ?>
