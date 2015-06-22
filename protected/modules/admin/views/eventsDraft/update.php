<?php
/* @var $this EventsDraftController */
/* @var $model EventsDraft */

$this->pageTitle = Yii::t('main', 'Update event draft');
?>

<h1><?php echo Yii::t('main', 'Update event draft'); ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>