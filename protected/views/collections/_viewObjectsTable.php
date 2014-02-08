<?php
/* @var $this CollectionsController */
/* @var $ObjectsDataProvider CActiveDataProvider */
/* @var $model Collections */
/* @var $tempCollectionsAllowedToUser array */
/* @var $collectionsToMoveTo array */
?>

<?php $this->renderPartial('_viewObjectsMenu', array('model' => $model, 'tempCollectionsAllowedToUser' => $tempCollectionsAllowedToUser, 'collectionsToMoveTo' => $collectionsToMoveTo)); ?>

<?php
$this->widget(
    'bootstrap.widgets.TbGridView',
    array(
        'type' => 'striped bordered',
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
                )
            ),
            array(
                'class' => 'CLinkColumn',
                'labelExpression' => '$data->name',
                'urlExpression' => 'Yii::app()->urlManager->createUrl("objects/view", array("id" => $data->id))',
                'header'=>'Название',
                'linkHtmlOptions' => array(
                    'class' => '_objectLinkInTableRow'
                )
            ),
            array(
                'value'=>'(!empty($data->author->initials) ? $data->author->initials : "")',
                'header'=>'Автор',
                'sortable' => false
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
                )
            )
        ),
        'showTableOnEmpty' => false,
        'emptyText' => ''
    )
);
?>

