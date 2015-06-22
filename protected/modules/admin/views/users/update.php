<?php
/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */
$this->pageTitle = Yii::t('main', 'Update profile');

Yii::app()->clientScript->registerScript('organizerFieldToggle', "
	$('#Users_role').change(function(){
		if ($(this).val() == 'organizer')
		{
			$('#organizerName').show(350);
		}
		else
		{
			$('#organizerName').hide(350);
		}
	});
");
Yii::app()->clientScript->registerScript('organizerFieldShow', "
	if ($('#Users_role').val() == 'organizer')
	{
		$('#organizerName').show(0);
	}
", CClientScript::POS_READY);
?>

<h1><?php echo Yii::t('main', 'Update profile'); ?></h1>

<div class="form">
	
<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id' => 'users-form',
    'type' => 'vertical',
	'enableClientValidation' => false,
	'clientOptions' => array(
		'validateOnSubmit' => true,
	),
)); ?>

<p class="note"><?php echo Yii::t('main', 'Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('main', 'are required'); ?>.</p>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->labelEx($model, 'email'); ?>
<?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 255)); ?>
<?php echo $form->error($model, 'email'); ?>

<?php echo $form->labelEx($model, 'displayName'); ?>
<?php echo $form->textField($model, 'displayName', array('size' => 60, 'maxlength' => 255)); ?>
<?php echo $form->error($model, 'displayName'); ?>

<?php echo $form->labelEx($model, 'socialNetwork'); ?>
<?php echo $form->textField($model, 'socialNetwork', array('size' => 60, 'maxlength' => 255)); ?>
<?php echo $form->error($model, 'socialNetwork'); ?>

<?php echo $form->labelEx($model, 'socialUserId'); ?>
<?php echo $form->textField($model, 'socialUserId', array('size' => 60, 'maxlength' => 255)); ?>
<?php echo $form->error($model, 'socialUserId'); ?>
	
<?php if (Yii::app()->user->checkAccess('admin') && ($model->authAssignment->itemname != 'admin')): ?>
	<?php echo $form->labelEx($model, 'role'); ?>
	<?php echo $form->dropDownList($model, 'role', Helper::getRoles(), 
		array('options' => array($model->authAssignment->itemname => array('selected' => 'selected')))); ?>
	<?php echo $form->error($model, 'role'); ?>

	<div id="organizerName" style="display:none">
		<?php echo $form->labelEx($model, 'organizerName'); ?>
		<?php echo $form->textField($model, 'organizerName'); ?>
		<?php echo $form->error($model, 'organizerName'); ?>
	</div>

	<?php echo $form->labelEx($model, 'state'); ?>
	<?php echo $form->dropDownList($model, 'state', Helper::getUserStates(),
	array('options' => array($model->state => array('selected' => 'selected')))); ?>
	<?php echo $form->error($model, 'state'); ?>
<?php endif; ?>

<br><br>
<?php $this->widget('bootstrap.widgets.TbButton', array(
	'buttonType' => 'submit',
	'type' => 'primary',
	'label' => Yii::t('main', 'Save'),
)); ?>

<?php $this->endWidget(); ?>

</div><!-- form -->