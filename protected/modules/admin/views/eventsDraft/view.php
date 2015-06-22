<?php
/* @var $this EventsDraftController */
/* @var $model EventsDraft */

$this->pageTitle = Yii::t('main', 'View event draft');
?>

<h1><?php echo Yii::t('main', 'View event draft'); ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data' => $model,
	'attributes' => array(
		array(
			'name' => 'placeName',
			'value' => $model->getPlaceName($model->placeId),
		),
		array(
			'name' => 'organizerName',
			'value' => $model->organizers->title,
		),
		array(
			'name' => 'authorName',
			'value' => $model->users->displayName,
		),
		array(
			'name' => 'categories',
			'value' => $model->getCategories(),
		),
		'title',
		'numberPeople',
		'description',
		array(
			'name' => 'eventDate',
			'value' => Helper::formatDate($model->eventDate),
		),
		array(
			'name' => 'state',
			'value' => Yii::t('main', ucfirst($model->state)),
		),
		array(
			'name' => 'updated',
			'value' => Helper::formatDate($model->updated),
		),
	),
)); ?>
