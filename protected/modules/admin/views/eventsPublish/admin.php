<?php
/* @var $this EventsPublishController */
/* @var $model EventsPublish */

$this->pageTitle = Yii::t('main', 'Manage events publish');

$this->widget('bootstrap.widgets.TbButtonGroup', array(
    'type' => 'success',
    'size' => 'small',
    'buttons' => array(
        array(
            'label' => Yii::t('main', 'Publish'), 
            'active' => true,
        ),
        array(
            'label' => Yii::t('main', 'Drafts'), 
            'url' => '/admin/eventsDraft/admin',
        ),
        array(
            'label' => Yii::t('main', 'Deleted'),
            'url' => '/admin/eventsDraft/removed',
            'visible' => Yii::app()->user->checkAccess('admin'),
        ),
    ),
)); 
echo '<br><br>';
?>

<h1><?php echo Yii::t('main', 'Manage events publish'); ?></h1>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'events-publish-grid',
    'type' => 'striped bordered condensed',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'selectionChanged' => 'function(id) { location.href = \'/admin/eventsPublish/view/id/\' + $.fn.yiiGridView.getSelection(id); }',
    'htmlOptions' => array('style' => 'cursor: pointer;'),
    'columns' => array(
        'title',
        array(
            'name' => 'placeName',
            'value' => 'CHtml::link($data->placesPublish->title, \'../placesPublish/view/id/\'.$data->placeId)',
            'type'  => 'html',
        ),
        array(
                'name' => 'authorName',
                'value' => 'CHtml::link($data->users->displayName, \'../users/view/id/\'.$data->users->id)',
                'type' => 'html',
        ),
        array(
                'name' => 'organizerName',
                'value' => '$data->getOrganizerLink()',
                'type'  => 'html',
        ),
        array(
            'name' => 'eventDate',
            'value' => 'Helper::formatDate($data->eventDate)',
            'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model' => $model, 
                'attribute' => 'eventDate',
                'language' => 'en-GB',
                'htmlOptions' => array(
                    'id' => 'eventDateFilterDatepicker',
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
            true),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{delete}',
            'visible' => Yii::app()->user->checkAccess('moderator')
        ),
    ),
)); ?>
