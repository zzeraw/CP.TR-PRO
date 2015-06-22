<?php
/* @var $this EventsDraftController */
/* @var $model EventsDraft */

Yii::app()->clientScript->registerScript('re-install-date-picker', "
	function reinstallDatePicker(id, data) {
		$('#searchDatepicker, #updateFilterDatepicker, #eventDateFilterDatepicker, #eventDateSearchDatepicker, #updatedSearchDatepicker').datepicker();
	}
");

$this->pageTitle = Yii::t('main', 'Manage events drafts');

$this->widget('bootstrap.widgets.TbButtonGroup', array(
    'type' => 'success',
    'size' => 'small',
    'buttons' => array(
        array(
            'label' => Yii::t('main', 'Publish'), 
            'url' => '/admin/eventsPublish/admin', 
        ),
        array(
            'label' => Yii::t('main', 'Drafts'), 
            'active' => true,
        ),
        array(
            'label' => Yii::t('main', 'Deleted'),
            'url' => '/admin/eventsDraft/removed',
            'visible' => Yii::app()->user->checkAccess('admin'),
        ),
    ),
)); 
echo '<br><br>';
?>

<h1><?php echo Yii::t('main', 'Manage events drafts'); ?></h1>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
        'id' => 'events-draft-grid',
        'type' => 'striped bordered condensed',
        'dataProvider' => $model->search(),
	'filter' => $model,
	'selectionChanged' => 'function(id) { location.href = \'/admin/eventsDraft/update/id/\' + $.fn.yiiGridView.getSelection(id); }',
	'htmlOptions' => array('style' => 'cursor: pointer;'),
	'afterAjaxUpdate' => 'reinstallDatePicker',
        'columns' => array(
            'title',
            
            array(
                'name' => 'placeName',
                'value' => '$data->getPlaceNameLink($data->placeId)',
                'type' => 'html',
            ),
            array(
                'name' => 'organizerName',
                'value' => '$data->getOrganizerNameLink($data->organizerId)',
                'type' => 'html',
            ),
            array(
                'name' => 'authorName',
                'value' => 'CHtml::link($data->users->displayName, \'../users/view/id/\'.$data->authorId)',
                'type' => 'html',
            ),
            array(
                'name' => 'eventDate',
                'value' => 'Helper::formatDate($data->eventDate)',
                'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $model, 
                    'attribute' => 'eventDate',
                    'language' => 'en-GB',
                    'htmlOptions' => array(
                        'id' => 'eventDateFilterDatepicker',
                        'size' => '10',
                    ),
                    'defaultOptions' => array(
                        'showOn' => 'focus', 
                        'dateFormat' => 'dd/mm/yy',
                        'showOtherMonths' => true,
                        'selectOtherMonths' => true,
                        'changeMonth' => true,
                        'changeYear' => true,
                        'showButtonPanel' => false,
                    )
                ), 
                true),
            ),
            array(
                'name' => 'state',
                'value' => 'Helper::getState($data->state)',
                'filter' => Helper::getEventStates(),
                'type' => 'raw',
                'htmlOptions' => array(
                    'style' => 'text-align: center;',
                ),
            ),
            array(
                'name' => 'updated',
                'value' => 'Helper::formatDate($data->updated)',
                'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $model, 
                    'attribute' => 'updated', 
                    'language' => 'en-GB',
                    'htmlOptions' => array(
                        'id' => 'updateFilterDatepicker',
                        'size' => '10',
                    ),
                    'defaultOptions' => array(
                        'showOn' => 'focus', 
                        'dateFormat' => 'dd/mm/yy',
                        'showOtherMonths' => true,
                        'selectOtherMonths' => true,
                        'changeMonth' => true,
                        'changeYear' => true,
                        'showButtonPanel' => false,
                    )
                ), 
                true),
            ),
            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'template' => '{delete}',
                'buttons' => array(
                    'delete' => array(
                        'visible' => '$data->state != \'approved\'',
                    ),
                ),
            ),
	),
)); ?>