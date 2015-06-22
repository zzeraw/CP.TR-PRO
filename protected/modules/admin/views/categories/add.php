<?php
/* @var $this CategoriesController */
/* @var $model Categories */

$this->pageTitle = Yii::t('main', 'Add category');
?>

<h1><?php echo Yii::t('main', 'Add category'); ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>