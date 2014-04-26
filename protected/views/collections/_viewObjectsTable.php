<?php
/* @var $this CollectionsController */
/* @var $ObjectsDataProvider CActiveDataProvider */
/* @var $model Collections */
/* @var $tempCollectionsAllowedToUser array */
/* @var $collectionsToMoveTo array */
?>

<?php $this->renderPartial('_viewObjectsMenu', array('model' => $model, 'tempCollectionsAllowedToUser' => $tempCollectionsAllowedToUser, 'collectionsToMoveTo' => !empty($collectionsToMoveTo) ? $collectionsToMoveTo : null)); ?>

<?php
$this->widget(
    'bootstrap.widgets.TbGridView',
    array(
        'type' => 'striped bordered condensed',
        //'responsiveTable' => true,
        'dataProvider' => $ObjectsDataProvider,
        'template' => '{items}'.PHP_EOL.'{pager}',
        'ajaxUpdate' => false,
        'columns' => array(
            array(
                'class' => 'CCheckBoxColumn',
                'value' => 'false',
                'checked' => 'false',
                'selectableRows' => 2,
                'checkBoxHtmlOptions' => array(
                    'class' => '_objectItem',
                    'data-object-id' => '' // айдишник объекта будет записываться сюда джаваскриптом
                ),
                'headerHtmlOptions' => array(
                    'class' => 'valignedMiddle halignedCenter hidden-print'
                ),
                'htmlOptions' => array(
                    'class' => 'hidden-print'
                ),
            ),
            array(
                'class' => 'CLinkColumn',
                'labelExpression' => '$data->name',
                'urlExpression' => 'Yii::app()->urlManager->createObjectUrl($data)',
                'header'=>'Название',
                'linkHtmlOptions' => array(
                    'class' => '_objectLinkInTableRow'
                ),
                'headerHtmlOptions' => array(
                    'class' => 'valignedMiddle halignedCenter'
                ),
                'htmlOptions' => array(
                    'class' => 'valignedMiddle'
                ),
            ),
            array(
                'value'=>'$data->getAuthorInitials()',
                'header' => Yii::t('objects', 'Автор'),
                'sortable' => false,
                'headerHtmlOptions' => array(
                    'class' => 'valignedMiddle halignedCenter'
                ),
                'htmlOptions' => array(
                    'class' => 'itemTableAuthor valignedMiddle'
                ),
            ),
            array(
                'value'=>'(!empty($data->period) ? $data->period : "")',
                'header' => Yii::t('objects', 'Период создания'),
                'sortable' => false,
                'headerHtmlOptions' => array(
                    'class' => 'valignedMiddle halignedCenter'
                ),
                'htmlOptions' => array(
                    'class' => 'itemTableCreationPeriod valignedMiddle',
                    'nowrap' => 'nowrap'
                ),
            ),
            array(
                'value'=>'(!empty($data->inventory_number) ? $data->inventory_number : "")',
                'header' => Yii::t('objects', 'Инвентарный номер'),
                'sortable' => false,
                'headerHtmlOptions' => array(
                    'class' => 'valignedMiddle halignedCenter'
                ),
                'htmlOptions' => array(
                    'class' => 'itemTableInventoryNumber valignedMiddle'
                ),
            ),
            array(
                'value'=>'(!empty($data->type->name) ? $data->type->name : "")',
                'header' => Yii::t('objects', 'Тип объекта'),
                'sortable' => false,
                'headerHtmlOptions' => array(
                    'class' => 'valignedMiddle halignedCenter'
                ),
                'htmlOptions' => array(
                    'class' => 'itemTableObjectType valignedMiddle'
                ),
            ),
            array(
                'value'=>'(!empty($data->size) ? $data->size : "")',
                'header' => Yii::t('common', 'Размер'),
                'sortable' => false,
                'headerHtmlOptions' => array(
                    'class' => 'valignedMiddle halignedCenter'
                ),
                'htmlOptions' => array(
                    'class' => 'itemTableSize valignedMiddle',
                    'nowrap' => 'nowrap'
                ),
            ),
            array(
                'header'=>'',
                'htmlOptions' => array(
                    'nowrap' => 'nowrap',
                    'class' => 'valignedMiddle hidden-print'
                ),
                'class'=>'bootstrap.widgets.TbButtonColumn',
                'viewButtonUrl' => 'Yii::app()->urlManager->createObjectUrl($data)',
                'updateButtonUrl'=> null,
                'deleteButtonUrl' => null,
                'buttons' => array(
                    'delete' => array('visible' => 'false')
                ),
                'headerHtmlOptions' => array(
                    'class' => 'valignedMiddle halignedCenter hidden-print'
                ),
            )
        ),
        'showTableOnEmpty' => false,
        'emptyText' => ''
    )
);
?>

