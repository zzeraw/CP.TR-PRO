<?php
/* @var $this DashboardController */
/* @var $eventsModel EventsDraft */
/* @var $placesModel PlacesDraft */

$this->pageTitle = Yii::t('main', 'Events for moderation');
?>
<h1><?php echo Yii::t('main', 'Events for moderation'); ?></h1>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'events-draft-grid',
    'type' => 'striped bordered condensed',
    'htmlOptions' => array('style' => 'cursor: pointer;'),
    'dataProvider' => $eventsModel->searchModerator(),
    'selectionChanged' => 'function(id) { location.href = \'/admin/eventsDraft/update/id/\' + $.fn.yiiGridView.getSelection(id); }',
    'columns' => array(
        'title',
	array(
            'name' => 'eventDate',
            'value' => 'Helper::formatDate($data->eventDate)',
        ),
	array(
            'name' => 'placeName',
            'value' => '$data->getPlaceNameLink($data->placeId)',
            'type' => 'html',
        ),
    ),
)); ?>

<h1><?php echo Yii::t('main', 'Places for moderation'); ?></h1>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id' => 'places-draft-grid',
	'htmlOptions' => array('style' => 'cursor: pointer;'),
	'selectionChanged' => 'function(id) { location.href = \'/admin/placesDraft/update/id/\' + $.fn.yiiGridView.getSelection(id); }',
    'type' => 'striped bordered condensed',
    'dataProvider' => $placesModel->moderatorSearch(),
    'columns' => array(
		'title',
		'address',
		'contacts',
		array(
			'name' => 'authorName',
			'value' => 'CHtml::link($data->users->displayName, \'../users/view/id/\'.$data->users->id)',
			'type' => 'html',
		),
	),
)); ?>

<h1><?php echo Yii::t('main', 'Organizers for moderation'); ?></h1>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id' => 'organizers-draft-grid',
	'htmlOptions' => array('style' => 'cursor: pointer;'),
	'selectionChanged' => 'function(id) { location.href = \'/admin/organizersDraft/update/id/\' + $.fn.yiiGridView.getSelection(id); }',
    'type' => 'striped bordered condensed',
    'dataProvider' => $organizersDraft->searchModerator(),
    'columns' => array(
		'title',
        array(
			'name' => 'userName',
			'value' => '$data->getUserLink()',
			'type' => 'raw',
		),
        array(
            'name' => 'updated',
			'value' => 'Helper::formatDate($data->updated)',
        ),
	),
)); ?>