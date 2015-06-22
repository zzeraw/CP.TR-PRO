<?php
/* @var $this PlacesPublishController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = Yii::t('main', 'Places');
?>

<h1><?php echo Yii::t('main', 'Places'); ?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider' => $dataProvider,
	'itemView' => '_view',
)); ?>
