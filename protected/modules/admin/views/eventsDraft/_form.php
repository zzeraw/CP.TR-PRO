<?php
/* @var $this EventsDraftController */
/* @var $model EventsDraft */
/* @var $form CActiveForm */

Yii::app()->clientScript->registerScript('autocomplete', "
	$('#EventsDraft_placeName').focus(function(){
		$(this).autocomplete('search');
		return false;
	});
	$('#EventsDraft_organizerName').focus(function(){
		$(this).autocomplete('search');
		return false;
	});
	$('#EventsDraft_categories').focus(function(){
		$(this).autocomplete('search');
		return false;
	});
");
?>

<div class="form">
	
<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id' => 'events-draft-form',
    'type' => 'vertical',
	'enableClientValidation' => false,
	'clientOptions' => array(
		'validateOnSubmit' => true,
	),
)); ?>
	
<p class="note"><?php echo Yii::t('main', 'Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('main', 'are required'); ?>.</p>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->labelEx($model, 'title'); ?>
<?php echo $form->textField($model, 'title', array('size' => 60,'maxlength' => 255)); ?>
<?php echo $form->error($model, 'title'); ?>

<?php echo $form->labelEx($model, 'placeName'); ?>
<?php $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
	'model' => $model,
	'attribute' => 'placeName',
	'source' => 'js:function(request, response) {
					$.getJSON(
						"'.$this->createUrl('eventsDraft/autocompletePlace').'", 
						{ term: request.term.split(/,s*/).pop() }, 
						response
					);
				}',
	'options' => array(
		'minLength' =>  '0',
		'showAnim' => 'fold',
	)
)); ?>
<?php echo $form->error($model, 'placeName'); ?>

<?php if (!Yii::app()->user->checkAccess('organizer')): ?>
	<?php echo $form->labelEx($model, 'organizerName'); ?>
	<?php $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
		'model' => $model,
		'attribute' => 'organizerName',
		'source' => 'js:function(request, response) {
						$.getJSON(
							"'.$this->createUrl('eventsDraft/autocompleteOrganizer').'", 
							{ term: request.term.split(/,s*/).pop() }, 
							response
						);
					}',
		'options' => array(
			'minLength' => '0',
			'showAnim' => 'fold',
		)
	)); ?>
	<?php echo $form->error($model, 'organizerName'); ?>
<?php endif; ?>
	
<?php echo $form->labelEx($model, 'categories'); ?>
<?php $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
	'model' => $model,
	'attribute' => 'categories',
	'source' => 'js:function(request, response) {
					$.getJSON(
						"'.$this->createUrl('eventsDraft/autocompleteCategory').'", 
						{ term: $.trim(request.term.split(/,s*/).pop()) }, 
						response
					);
				}',
	'options' => array(
		'minLength' => 0,
		'search' => 'js: function() {
			var term = this.value.split(/,s*/).pop();
		}',
		'focus' => 'js: function() {
			 return false;
		}',
		'select' => 'js: function(event, ui) {
			var terms =  this.value.split(/,s*/);
			var size = terms.length;
			for (var i = 0; i < size; ++i)
			{
				terms[i] = $.trim(terms[i]);
			}
			terms.pop();
			terms.push(ui.item.value);
			terms.push("");
			this.value = terms.join(", ");
			return false;
		}',
	),
	'htmlOptions' => array(
		'style' => 'width: 250px;',
	),
)); ?>
<?php echo $form->error($model, 'categories'); ?>

<?php echo $form->labelEx($model, 'numberPeople'); ?>
<?php echo $form->textField($model, 'numberPeople', array('size' => 5,'maxlength' => 11)); ?>
<?php echo $form->error($model, 'numberPeople'); ?>

<?php echo $form->labelEx($model, 'description'); ?>
<?php echo $form->textArea($model, 'description', array('rows' => 6, 'cols' => 50)); ?>
<?php echo $form->error($model, 'description'); ?>

<?php echo $form->labelEx($model, 'ticketPrice'); ?>
<?php echo $form->textField($model, 'ticketPrice', array('size' => 5,'maxlength' => 11)); ?>
<?php echo $form->error($model, 'ticketPrice'); ?>

<br><br>
<label class="checkbox" style="font-size: 14px;">
    <?php echo $form->checkBox($model,'ticketSales', array('checked' => 'checked')).' '.Yii::t('main', 'Ticket sales'); ?> 
</label>
<br>

<?php echo $form->labelEx($model, 'eventDate'); ?>
<?php $this->widget('CJuiDateTimePicker', array(
	'model' => $model,
	'attribute' => 'eventDate',
	'options' => array(
		'dateFormat' => 'dd/mm/yy',
		'showSecond' => false,
	),
	'htmlOptions' => array(
			'id' => 'datepicker',
			'size' => '20',
		),
)); ?>
<?php echo $form->error($model, 'eventDate'); ?>

<br><br>
<?php if ($model->isNewRecord): ?>
<?php $this->widget('bootstrap.widgets.TbButton', array(
	'buttonType' => 'submit', 'type' => 'primary', 'label' => Yii::t('main', 'Save draft'), 'htmlOptions' => array('value' => 'Save draft', 'name' => 'EventsDraft[state]')
)); ?>
<?php else: ?>
<?php $this->widget('bootstrap.widgets.TbButton', array(
	'buttonType' => 'submit', 'type' => 'primary', 'label' => Yii::t('main', 'Update draft'), 'htmlOptions' => array('value' => 'Update draft', 'name' => 'EventsDraft[state]')
)); ?>
<?php endif; ?>

<?php if (Yii::app()->user->checkAccess('moderator')): ?>
<?php $this->widget('bootstrap.widgets.TbButton', array(
	'buttonType' => 'submit', 'type' => 'primary', 'label' => Yii::t('main', 'Publish event'), 'htmlOptions' => array('value' => 'Publish event', 'name' => 'EventsDraft[state]')
)); ?>
<?php else: ?>
<?php $this->widget('bootstrap.widgets.TbButton', array(
	'buttonType' => 'submit', 'type' => 'primary', 'label' => Yii::t('main', 'Send to moderate'), 'htmlOptions' => array('value' => 'Send to moderate', 'name' => 'EventsDraft[state]')
)); ?>
<?php endif; ?>

<?php $this->endWidget(); ?>

</div><!-- form -->