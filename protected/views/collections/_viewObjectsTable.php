<?php
/* @var $this CollectionsController */
/* @var $ObjectsDataProvider CActiveDataProvider */
/* @var $model Collections */
?>

<?php $this->renderPartial('_viewObjectsMenu', array('model' => $model)); ?>

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
                'value' => 'return null;',
                'checked' => 'return null;',
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
                    'delete' => array('visible' => 'return false;')
                )
            )
        ),
        'showTableOnEmpty' => false,
        'emptyText' => ''
    )
);
?>

