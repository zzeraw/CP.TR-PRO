<?php
/* @var $this PlacesDraftController */
/* @var $model PlacesDraft */
$this->pageTitle = Yii::t('main', 'Add place');
?>

<h1><?php echo Yii::t('main', 'Add place'); ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>