<?php
/* @var $this EventsPublishController */
/* @var $model EventsPublish */
$this->pageTitle = Yii::t('main', 'View event publish');
?>

<h1><?php echo Yii::t('main', 'View event publish'); ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data' => $model,
	'attributes' => array(
            array(
                'name' => 'placeName',
                'value' => $model->placesPublish->title,
            ),
            array(
                'name' => 'authorName',
                'value' => $model->users->displayName,
            ),
            array(
                'name' => 'organizerName',
                'value' => $model->getOrganizer(),
            ),
            array(
                'name' => 'categories',
                'value' => $model->getCategories(),
            ),
            'title',
            'numberPeople',
            'description',
            array(
                'name' => 'eventDate',
                'value' => Helper::formatDate($model->eventDate),
            ),
            'ticketPrice',
            array(
                'label' => '',
                'value' => Yii::t('main', 'Ticket sales'),
                'visible' => ($model->ticketSales == 1),
            ),
            array(
                'value' => '<a href="/admin/eventsPublish/getTickets/id/'.$model->id.'">'.Yii::t('main', 'Get tickets').'</a>',
                'type' => 'html',
            )
	),
)); ?>
