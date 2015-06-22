<?php
/* @var $this CategoriesController */
/* @var $model Categories */

$this->pageTitle = Yii::t('main', 'Update сategory'); 

?>

<h1><?php echo Yii::t('main', 'Update сategory'); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>