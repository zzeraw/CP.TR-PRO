<?php
/* @var $this PlacesDraftController */
/* @var $model PlacesDraft */

Yii::app()->clientScript->registerScript('re-install-date-picker', "
	function reinstallDatePicker(id, data) {
		$('#searchDatepicker, #filterDatepicker').datepicker();
	}
");

$this->widget('bootstrap.widgets.TbButtonGroup', array(
    'type' => 'success',
    'size' => 'small',
    'buttons' => array(
        array(
            'label' => Yii::t('main', 'Publish'), 
            'url' => '/admin/placesPublish/admin', 
        ),
        array(
            'label' => Yii::t('main', !Yii::app()->user->checkAccess('moderator ') ? 'My drafts' : 'Drafts'), 
            'url' => '/admin/placesDraft/admin',
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

<h1><?php echo Yii::t('main', 'Management of remote sites'); ?></h1>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id' => 'places-draft-grid',
    'type' => 'striped bordered condensed',
    'dataProvider' => $model->searchRemoved(),
	'filter' => $model,
	'afterAjaxUpdate' => 'reinstallDatePicker',
    'template' => '{items}',
    'columns'=>array(
		'title',
		'address',
		array(
			'name' => 'state',
			'value' => 'Yii::t(\'main\', ucfirst($data->state))',
			'filter' => Helper::getPlaceStates(),
		),
		array(
            'name' => 'updated',
            'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model' => $model, 
                'attribute' => 'updated', 
				'language' => 'en-GB',
                'htmlOptions' => array(
                    'id' => 'filterDatepicker',
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
			'name' => 'authorName',
			'value' => 'CHtml::link($data->users->displayName, \'../users/view/id/\'.$data->users->id)',
			'type' => 'html',
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template' => '{restore}',
			'buttons'=>array
    		(
				'restore' => array
				(
					'label'=>Yii::t('main', 'Restore place'),
					'icon'=>'icon-share',
					'url'=>'Yii::app()->createUrl("admin/placesDraft/restore", array("id"=>$data->id))',
				),
			),
		),
    ),
)); ?>
