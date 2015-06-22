<?php
/* @var $this CategoriesController */
/* @var $model Categories */
$this->pageTitle = Yii::t('main', 'Manage categories');
?>

<h1><?php echo Yii::t('main', 'Manage categories'); ?></h1>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id' => 'categories-grid',
    'type' => 'striped bordered condensed',
    'dataProvider' => $model->search(),
	'filter' => $model,
	'selectionChanged' => 'function(id) { location.href = \'/admin/categories/update/id/\' + $.fn.yiiGridView.getSelection(id); }',
	'htmlOptions' => array('style' => 'cursor: pointer;'),
    'columns' => array(
		'title',
	),
)); ?>
