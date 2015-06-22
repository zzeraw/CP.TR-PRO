<?php
/* @var $this EventsDraftController */
/* @var $data EventsDraft */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id' => $data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('placeName')); ?>:</b>
	<?php echo CHtml::encode($data->placesPublish->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('organizerName')); ?>:</b>
	<?php echo CHtml::encode($data->organizers->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('authorName')); ?>:</b>
	<?php echo CHtml::encode($data->users->displayName); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('eventDate')); ?>:</b>
	<?php echo CHtml::encode(Helper::formatDate($data->eventDate)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('state')); ?>:</b>
	<?php echo CHtml::encode($data->state); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updated')); ?>:</b>
	<?php echo CHtml::encode(Helper::formatDate($data->updated)); ?>
	<br />

</div>