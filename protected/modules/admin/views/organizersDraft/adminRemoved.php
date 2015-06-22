<?php
/* @var $this OrganizersDraftController */
/* @var $model OrganizersDraft */

Yii::app()->clientScript->registerScript('re-install-date-picker', "
	function reinstallDatePicker(id, data) {
		$('#updateFilterDatepicker').datepicker();
	}
");

$this->widget('bootstrap.widgets.TbButtonGroup', array(
    'type' => 'success',
    'size' => 'small',
    'buttons' => array(
        array(
            'label' => Yii::t('main', 'Publish'), 
            'url' => '/admin/organizersPublish/admin', 
        ),
        array(
            'label' => Yii::t('main', !Yii::app()->user->checkAccess('moderator ') ? 'My drafts' : 'Drafts'), 
            'url' => '/admin/organizersDraft/admin',
        ),
        array(
            'label' => Yii::t('main', 'Deleted'),
            'visible' => Yii::app()->user->checkAccess('admin'),
            'active' => true,
        ),
    ),
)); 
echo '<br><br>';
?>

<h1><?php echo Yii::t('main', 'Manage organizers drafts'); ?></h1>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id' => 'organizers-draft-grid',
    'type' => 'striped bordered condensed',
    'dataProvider' => $model->searchRemoved(),
	'filter' => $model,
	'afterAjaxUpdate' => 'reinstallDatePicker',
	'columns' => array(
		'title',
		array(
			'name' => 'userName',
			'value' => '$data->getUserLink()',
			'type' => 'raw',
		),
		array(
			'name' => 'state',
			'value' => 'Yii::t(\'main\', ucfirst($data->state))',
			'filter' => Helper::getEventStates(),
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
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template' => '{restore}',
			'buttons' => array
    		(
				'restore' => array
				(
					'label' => Yii::t('main', 'Restore Event'),
					'icon'=>'icon-share',
					'url' => 'Yii::app()->createUrl("admin/organizersDraft/restore", array("id"=>$data->id))',
				),
			),
		),
	),
)); ?>
