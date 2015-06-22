<?php
/* @var $this PlacesPublishController */
/* @var $model PlacesPublish */

$this->pageTitle = Yii::t('main', 'Manage places publish');

$this->widget('bootstrap.widgets.TbButtonGroup', array(
    'type' => 'success',
    'size' => 'small',
    'buttons' => array(
        array(
            'label' => Yii::t('main', 'Publish'), 
            'active' => true,
        ),
        array(
            'label' => Yii::t('main', !Yii::app()->user->checkAccess('moderator ') ? 'My drafts' : 'Drafts'), 
            'url' => '/admin/placesDraft/admin',
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


<h1><?php echo Yii::t('main', 'Manage places publish'); ?></h1>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id' => 'places-publish-grid',
    'type' => 'striped bordered condensed',
    'dataProvider' => $model->search(),
	'filter' => $model,
	'selectionChanged' => 'function(id) { location.href = \'/admin/placesPublish/view/id/\' + $.fn.yiiGridView.getSelection(id); }',
	'htmlOptions' => array('style' => 'cursor: pointer;'),
    'columns' => array(
		'title',
		'address',
		array(
			'name' => 'authorName',
			'value' => '$data->users->displayName',
			'value' => 'CHtml::link($data->users->displayName, \'../users/view/id/\'.$data->users->id)',
			'type' => 'html',
		),
    ),
)); ?>
