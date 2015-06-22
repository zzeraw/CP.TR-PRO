<?php
/* @var $this UsersController */
/* @var $model Requests */

$this->pageTitle = Yii::t('main', 'Add user');
?>

<h1><?php echo Yii::t('main', 'Add user'); ?></h1>

<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id' => 'requests-form',
    'type' => 'vertical',
	'enableClientValidation' => false,
	'clientOptions' => array(
		'validateOnSubmit' => true,
	),
)); ?>

<p class="note"><?php echo Yii::t('main', 'Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('main', 'are required'); ?>.</p>
	
<?php echo $form->labelEx($model, 'email'); ?>
<?php echo $form->textField($model, 'email'); ?>
<?php echo $form->error($model, 'email'); ?>

<?php echo $form->labelEx($model, 'role'); ?>
<?php echo $form->dropDownList($model, 'role', Helper::getRoles()); ?>
<?php echo $form->error($model, 'role'); ?>

<br><br>
<?php $this->widget('bootstrap.widgets.TbButton', array(
	'buttonType' => 'submit',
	'type' => 'primary',
	'label' => Yii::t('main', 'Add'),
)); ?>

<?php $this->endWidget(); ?>
</div>