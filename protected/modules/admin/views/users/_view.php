<?php
/* @var $this UsersController */
/* @var $data Users */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id' => $data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('displayName')); ?>:</b>
	<?php echo CHtml::encode($data->displayName); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('socialNetwork')); ?>:</b>
	<?php echo CHtml::encode($data->socialNetwork); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('socialUserId')); ?>:</b>
	<?php echo CHtml::encode($data->socialUserId); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('role')); ?>:</b>
	<?php echo CHtml::encode($data->authAssignment->itemname); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('state')); ?>:</b>
	<?php echo CHtml::encode($data->state); ?>
	<br />


</div>