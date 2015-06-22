<?php
/* @var $this PlacesDraftController */
/* @var $model PlacesDraft */

Yii::app()->clientScript->registerScript('re-install-date-picker', "
	function reinstallDatePicker(id, data) {
		$('#searchDatepicker, #filterDatepicker').datepicker();
	}
");

$this->pageTitle = Yii::t('main', 'Manage places drafts');

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
            'active' => true,
        ),
        array(
            'label' => Yii::t('main', 'Deleted'),
            'url' => '/admin/placesDraft/removed',
            'visible' => Yii::app()->user->checkAccess('admin'),
        ),
    ),
)); 
echo '<br><br>';
?>

<h1><?php echo Yii::t('main', 'Manage places drafts'); ?></h1>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'places-draft-grid',
    'type' => 'striped bordered condensed',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'selectionChanged' => 'function(id) { location.href = \'/admin/placesDraft/update/id/\' + $.fn.yiiGridView.getSelection(id); }',
    'htmlOptions' => array('style' => 'cursor: pointer;'),
    'columns' => array(
	'title',
	'address',
	array(
        'name' => 'state',
		'value' => 'Helper::getState($data->state)',
		'filter' => Helper::getPlaceStates(),
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
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{delete}',
            'buttons' => array(
                'delete' => array(
                    'visible' => '!$data->placeIsPublish()',
                ),
            ),
        ),
    ),
)); ?>
