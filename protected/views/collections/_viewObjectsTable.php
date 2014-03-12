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
        'template' => "{items}",
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
                    'class' => 'valignedMiddle halignedCenter'
                ),
            ),
            array(
                'class' => 'CLinkColumn',
                'labelExpression' => '$data->name',
                'urlExpression' => 'Yii::app()->urlManager->createUrl("objects/view", array("id" => $data->id))',
                'header'=>'Название',
                'linkHtmlOptions' => array(
                    'class' => '_objectLinkInTableRow'
                ),
                'headerHtmlOptions' => array(
                    'class' => 'valignedMiddle halignedCenter'
                ),
            ),
            array(
                'value'=>'(!empty($data->author->initials) ? $data->author->initials : "")',
                'header' => Yii::t('objects', 'Автор'),
                'sortable' => false,
                'headerHtmlOptions' => array(
                    'class' => 'valignedMiddle halignedCenter'
                ),
                'htmlOptions' => array(
                    'class' => 'itemTableAuthor'
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
                    'class' => 'itemTableCreationPeriod'
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
                    'class' => 'itemTableInventoryNumber'
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
                    'class' => 'itemTableObjectType'
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
                    'class' => 'itemTableSize'
                ),
            ),
            array(
                'header'=>'Действия',
                'htmlOptions' => array('nowrap'=>'nowrap'),
                'class'=>'bootstrap.widgets.TbButtonColumn',
                'viewButtonUrl' => 'Yii::app()->urlManager->createUrl("objects/view", array("id" => $data->id));',
                'updateButtonUrl'=> null,
                'deleteButtonUrl' => null,
                'buttons' => array(
                    'delete' => array('visible' => 'false')
                ),
                'headerHtmlOptions' => array(
                    'class' => 'valignedMiddle halignedCenter'
                ),
            )
        ),
        'showTableOnEmpty' => false,
        'emptyText' => ''
    )
);
?>

