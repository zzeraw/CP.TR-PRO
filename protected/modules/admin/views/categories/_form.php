<?php
/* @var $this CategoriesController */
/* @var $model Categories */
/* @var $form CActiveForm */
?>

<div class="form">
	
<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id' => 'categories-form',
    'type' => 'vertical',
	'enableClientValidation' => false,
	'clientOptions' => array(
		'validateOnSubmit' => true,
	),
)); ?>

<p class="note"><?php echo Yii::t('main', 'Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('main', 'are required'); ?>.</p>	

<?php echo $form->errorSummary($model); ?>

<?php echo $form->labelEx($model, 'title'); ?>
<?php echo $form->textField($model, 'title', array('size' => 60, 'maxlength' => 255)); ?>
<?php echo $form->error($model, 'title'); ?>

<br><br>
<?php $this->widget('bootstrap.widgets.TbButton', array(
	'buttonType' => 'submit',
	'type' => 'primary',
	'label' => ($model->isNewRecord ? Yii::t('main', 'Add') : Yii::t('main', 'Save')),
)); ?>

<?php $this->endWidget(); ?>

</div><!-- form -->