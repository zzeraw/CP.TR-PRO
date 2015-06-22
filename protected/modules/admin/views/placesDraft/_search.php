<?php
/* @var $this PlacesDraftController */
/* @var $model PlacesDraft */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form = $this->beginWidget('CActiveForm', array(
	'action' => Yii::app()->createUrl($this->route),
	'method' => 'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model, 'title'); ?>
		<?php echo $form->textField($model, 'title', array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'address'); ?>
		<?php echo $form->textField($model, 'address', array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'state'); ?>
		<?php echo $form->dropDownList($model, 'state', Helper::getPlaceStatesWithEmpty()); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'updated'); ?>
		<?php echo $form->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model' => $model, 
                'attribute' => 'updated',
				'language' => 'en-GB',
                'htmlOptions' => array(
                    'id' => 'searchDatepicker',
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
            true); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model, 'authorName'); ?>
		<?php echo $form->textField($model, 'authorName'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('main', 'Search')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->