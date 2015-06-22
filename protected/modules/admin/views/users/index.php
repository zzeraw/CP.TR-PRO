<?php
/* @var $this UsersController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = Yii::t('main', 'Users');
?>

<h1><?php echo Yii::t('main', 'Users'); ?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider' => $dataProvider,
	'itemView' => '_view',
)); ?>
