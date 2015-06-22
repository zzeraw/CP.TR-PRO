<?php
/* @var $this OrganizersPublishController */
/* @var $model OrganizersPublish */

$this->widget('bootstrap.widgets.TbButtonGroup', array(
    'type' => 'success',
    'size' => 'small',
    'buttons' => array(
        array(
            'label' => Yii::t('main', 'Publish'), 
            'active' => true,
        ),
        array(
            'label' => Yii::t('main', 'Drafts'), 
            'url' => '/admin/organizersDraft/admin',
        ),
        array(
            'label' => Yii::t('main', 'Deleted'),
            'url' => '/admin/organizersDraft/removed',
            'visible' => Yii::app()->user->checkAccess('admin'),
        ),
    ),
)); 
echo '<br><br>';
?>

<h1><?php echo Yii::t('main', 'Manage organizers publishes') ?></h1>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id' => 'organizers-publish-grid',
    'type' => 'striped bordered condensed',
    'dataProvider' => $model->search(),
	'filter' => $model,
	'selectionChanged' => 'function(id) { location.href = \'/admin/organizersPublish/view/id/\' + $.fn.yiiGridView.getSelection(id); }',
	'htmlOptions' => array('style' => 'cursor: pointer;'),
	'columns' => array(
		'title',
		array(
			'name' => 'userName',
			'value' => '$data->getUserLink()',
			'type' => 'raw',
		),
		'contacts',
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
			'template' => '{delete}',
            'visible' => Yii::app()->user->checkAccess('admin'),
        ),
	),
)); ?>
