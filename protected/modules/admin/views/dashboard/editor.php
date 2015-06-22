<?php
/* @var $this DashboardController */
/* @var $model EventsDraft */

Yii::app()->clientScript->registerScript('re-install-date-picker', "
	function reinstallDatePicker(id, data) {
		$('#eventDateFilterDatepicker').datepicker();
	}
");

$this->pageTitle = Yii::t('main', 'My events');
?>
<h1><?php echo Yii::t('main', 'My events'); ?></h1>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id' => 'events-draft-grid',
    'type' => 'striped bordered condensed',
	'selectionChanged' => 'function(id) { location.href = \'/admin/eventsDraft/update/id/\' + $.fn.yiiGridView.getSelection(id); }',
	'htmlOptions' => array('style' => 'cursor: pointer;'),
    'dataProvider' => $model->searchEditor(),
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
		array(
			'name' => 'state',
			'value' => 'Helper::editorModerationState($data->state, $data->id)',
			'type' => 'html',
		),
		array(
			'class' => 'bootstrap.widgets.TbButtonColumn',
			'template' => '{delete}',
			'buttons' => array(
				'delete' => array(
					'url' => 'Yii::app()->createUrl("/admin/EventsDraft/delete/", array("id" => $data->id))',
				)
			)
		),
	),
)); ?>