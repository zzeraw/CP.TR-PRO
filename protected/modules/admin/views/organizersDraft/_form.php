<?php
/* @var $this OrganizersDraftController */
/* @var $model OrganizersDraft */
/* @var $form CActiveForm */

Yii::app()->clientScript->registerScript('userNameAutocomplete', "
	$('#OrganizersDraft_userName').focus(function(){
		$(this).autocomplete('search');
		return false;
	});
");
?>

<div class="form">
	
<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id' => 'organizers-draft-form',
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

<?php echo $form->labelEx($model, 'userName'); ?>
<?php $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
	'model' => $model,
	'attribute' => 'userName',
	'source' => 'js:function(request, response) {
					$.getJSON(
						"'.$this->createUrl('organizersDraft/autocompleteUserName').'", 
						{ term: request.term.split(/,s*/).pop() }, 
						response
					);
				}',
	'options' => array(
		'minLength' =>  '0',
		'showAnim' => 'fold',
	)
)); ?>
<?php echo $form->error($model, 'userName'); ?>

<?php echo $form->labelEx($model, 'contacts'); ?>
<?php echo $form->textArea($model, 'contacts', array('rows' => 6, 'cols' => 50)); ?>
<?php echo $form->error($model, 'contacts'); ?>

<br><br>
<?php if ($model->isNewRecord): ?>
<?php $this->widget('bootstrap.widgets.TbButton', array(
	'buttonType' => 'submit', 'type' => 'primary', 'label' => Yii::t('main', 'Save draft'), 'htmlOptions' => array('value' => 'Save draft', 'name' => 'OrganizersDraft[state]')
)); ?>
<?php else: ?>
<?php $this->widget('bootstrap.widgets.TbButton', array(
	'buttonType' => 'submit', 'type' => 'primary', 'label' => Yii::t('main', 'Update draft'), 'htmlOptions' => array('value' => 'Update draft', 'name' => 'OrganizersDraft[state]')
)); ?>
<?php endif; ?>

<?php if (Yii::app()->user->checkAccess('moderator')): ?>
<?php $this->widget('bootstrap.widgets.TbButton', array(
	'buttonType' => 'submit', 'type' => 'primary', 'label' => Yii::t('main', 'Publish organizer'), 'htmlOptions' => array('value' => 'Publish organizer', 'name' => 'OrganizersDraft[state]')
)); ?>
<?php else: ?>
<?php $this->widget('bootstrap.widgets.TbButton', array(
	'buttonType' => 'submit', 'type' => 'primary', 'label' => Yii::t('main', 'Send to moderate'), 'htmlOptions' => array('value' => 'Send to moderate', 'name' => 'OrganizersDraft[state]')
)); ?>
<?php endif; ?>

<?php $this->endWidget(); ?>

</div><!-- form -->