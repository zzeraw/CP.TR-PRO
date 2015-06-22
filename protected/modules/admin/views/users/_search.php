<?php
/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action' => Yii::app()->createUrl($this->route),
	'method' => 'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model, 'email'); ?>
		<?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'displayName'); ?>
		<?php echo $form->textField($model, 'displayName', array('size' => 60, 'maxlength' => 255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'socialNetwork'); ?>
		<?php echo $form->textField($model, 'socialNetwork', array('size' => 60, 'maxlength' => 255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'socialUserId'); ?>
		<?php echo $form->textField($model, 'socialUserId', array('size' => 60, 'maxlength' => 255)); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model, 'role'); ?>
		<?php echo $form->dropDownList($model, 'role', Helper::getRolesWithEmpty()); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'state'); ?>
		<?php echo $form->dropDownList($model, 'state', Helper::getUserStatesWithEmpty()); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('main', 'Search')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->