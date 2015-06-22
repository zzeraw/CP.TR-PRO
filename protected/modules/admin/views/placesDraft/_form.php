<?php
/* @var $this PlacesDraftController */
/* @var $model PlacesDraft */
/* @var $form CActiveForm */
?>

<div class="form">
	
<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id' => 'places-draft-form',
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

<?php echo $form->labelEx($model, 'address'); ?>
<?php echo $form->textField($model, 'address', array('size' => 60, 'maxlength' => 255)); ?>
<?php echo $form->error($model, 'address'); ?>

<?php echo $form->labelEx($model, 'latitude'); ?>
<?php echo $form->textField($model, 'latitude'); ?>
<?php echo $form->error($model, 'latitude'); ?>

<?php echo $form->labelEx($model, 'longitude'); ?>
<?php echo $form->textField($model, 'longitude'); ?>
<?php echo $form->error($model, 'longitude'); ?>

<?php echo $model->showMap(); ?>
<br>

<?php echo $form->labelEx($model, 'contacts'); ?>
<?php echo $form->textArea($model, 'contacts', array('rows' => 6, 'cols' => 50)); ?>
<?php echo $form->error($model, 'contacts'); ?>

<br><br>
<?php if ($model->isNewRecord): ?>
<?php $this->widget('bootstrap.widgets.TbButton', array(
	'buttonType' => 'submit', 'type' => 'primary', 'label' => Yii::t('main', 'Save draft'), 'htmlOptions' => array('value' => 'Save draft', 'name' => 'PlacesDraft[state]')
)); ?>
<?php else: ?>
<?php $this->widget('bootstrap.widgets.TbButton', array(
	'buttonType' => 'submit', 'type' => 'primary', 'label' => Yii::t('main', 'Update draft'), 'htmlOptions' => array('value' => 'Update draft', 'name' => 'PlacesDraft[state]')
)); ?>
<?php endif; ?>

<?php if (Yii::app()->user->checkAccess('moderator')): ?>
<?php $this->widget('bootstrap.widgets.TbButton', array(
	'buttonType' => 'submit', 'type' => 'primary', 'label' => Yii::t('main', 'Publish place'), 'htmlOptions' => array('value' => 'Publish place','name' => 'PlacesDraft[state]')
)); ?>
<?php else: ?>
<?php $this->widget('bootstrap.widgets.TbButton', array(
	'buttonType' => 'submit', 'type' => 'primary', 'label' => Yii::t('main', 'Send to moderate'), 'htmlOptions' => array('value' => 'Send to moderate', 'name' => 'PlacesDraft[state]')
)); ?>
<?php endif; ?>

<?php $this->endWidget(); ?>

</div><!-- form -->