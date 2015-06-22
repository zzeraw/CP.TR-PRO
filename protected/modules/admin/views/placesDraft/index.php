<?php
/* @var $this PlacesDraftController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = Yii::t('main', 'Management of remote sites');
?>

<h1><?php echo Yii::t('main', 'Management of remote sites'); ?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider' => $dataProvider,
	'itemView' => '_view',
)); ?>
