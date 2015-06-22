<?php
/* @var $this CategoriesController */
/* @var $model Categories */

$this->pageTitle = Yii::t('main', 'View сategory');

?>

<h1><?php echo Yii::t('main', 'View сategory'); ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data' => $model,
	'attributes' => array(
		'title',
	),
)); ?>
