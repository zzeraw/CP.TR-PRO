<?php
/* @var $this EventsDraftController */
/* @var $dataProvider CActiveDataProvider */
?>

<h1>Events Drafts</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider' => $dataProvider,
	'itemView' => '_view',
)); ?>
