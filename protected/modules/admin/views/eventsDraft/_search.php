<?php
/* @var $this EventsDraftController */
/* @var $model EventsDraft */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form = $this->beginWidget('CActiveForm', array(
	'action' => Yii::app()->createUrl($this->route),
	'method' => 'get',
)); ?>


	<div class="row">
		<?php echo $form->label($model, 'placeName'); ?>
		<?php echo $form->textField($model, 'placeName', array('size' => 10, 'maxlength' => 10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'organizerName'); ?>
		<?php echo $form->textField($model, 'organizerName', array('size' => 10, 'maxlength' => 10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'authorName'); ?>
		<?php echo $form->textField($model, 'authorName', array('size' => 10, 'maxlength' => 10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'title'); ?>
		<?php echo $form->textField($model, 'title', array('size' => 60, 'maxlength' => 255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'eventDate'); ?>
		<?php echo $form->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model' => $model, 
                'attribute' => 'eventDate',
				'language' => 'en-GB',
                'htmlOptions' => array(
                    'id' => 'eventDateSearchDatepicker',
                    'size' => '10',
                ),
                'defaultOptions' => array(
                    'showOn' => 'focus', 
                    'dateFormat' => 'dd/mm/yy',
                    'showOtherMonths' => true,
                    'selectOtherMonths' => true,
                    'changeMonth' => true,
                    'changeYear' => true,
                    'showButtonPanel' => false,
                )
            ), 
            true); 
		?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'state'); ?>
		<?php echo $form->dropDownList($model, 'state', Helper::getEventStatesWithEmpty()); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'updated'); ?>
		<?php echo $form->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model' => $model, 
                'attribute' => 'updated',
				'language' => 'en-GB',
                'htmlOptions' => array(
                    'id' => 'updatedSearchDatepicker',
                    'size' => '10',
                ),
                'defaultOptions' => array(
                    'showOn' => 'focus', 
                    'dateFormat' => 'dd/mm/yy',
                    'showOtherMonths' => true,
                    'selectOtherMonths' => true,
                    'changeMonth' => true,
                    'changeYear' => true,
                    'showButtonPanel' => false,
                )
            ), 
            true); 
		?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('main', 'Search')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->