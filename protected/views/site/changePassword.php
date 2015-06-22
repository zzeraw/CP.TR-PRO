<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle = Yii::t('main', 'Change password');
?>

<div class="form">
<h1><?php echo Yii::t('main', 'Change password'); ?></h1>

<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id' => 'change-password-form',
    'type' => 'vertical',
	'enableClientValidation' => true,
	'clientOptions' => array(
		'validateOnSubmit' => true,
	),
)); ?>

<p class="note"><?php echo Yii::t('main', 'Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('main', 'are required'); ?>.</p>

<?php echo $form->labelEx($model, 'password'); ?>
<?php echo $form->passwordField($model, 'password'); ?>
<?php echo $form->error($model, 'password'); ?>

<?php echo $form->labelEx($model, 'confirmPassword'); ?>
<?php echo $form->passwordField($model, 'confirmPassword'); ?>
<?php echo $form->error($model, 'confirmPassword'); ?>

<br>
<?php $this->widget('bootstrap.widgets.TbButton', array(
	'buttonType' => 'submit',
	'type' => 'primary',
	'label' => Yii::t('main', 'Save'),
)); ?>

<?php $this->endWidget(); ?>
</div><!-- form -->
