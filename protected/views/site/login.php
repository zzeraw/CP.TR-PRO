<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle = Yii::app()->name . ' - '.Yii::t('main', 'Login');
?>

<div class="form">
<h1><?php echo Yii::t('main', 'Login'); ?></h1>

<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id' => 'login-form',
    'type' => 'vertical',
	'enableClientValidation' => true,
	'clientOptions' => array(
		'validateOnSubmit' => true,
	),
)); ?>

<p class="note"><?php echo Yii::t('main', 'Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('main', 'are required'); ?>.</p>
	
<?php echo $form->errorSummary($model); ?>

<?php echo $form->labelEx($model, 'email'); ?>
<?php echo $form->textField($model, 'email'); ?>
<?php echo $form->error($model, 'email'); ?>

<?php echo $form->labelEx($model, 'password'); ?>
<?php echo $form->passwordField($model, 'password'); ?>
<?php echo $form->error($model, 'password'); ?>
	
<br><a href="/site/forgotpassword"><?php echo Yii::t('main', 'Forgot password?'); ?></a>

<br><br>
<?php $this->widget('bootstrap.widgets.TbButton', array(
	'buttonType' => 'submit',
	'type' => 'primary',
	'label' => Yii::t('main', 'Login'),
)); ?>

<?php $this->endWidget(); ?>
</div><!-- form -->
