<?php
/* @var $this CollectionsController */
/* @var $ChildCollectionsDataProvider CActiveDataProvider */
/* @var $model Collections */
?>

<?php $this->renderPartial('_viewChildCollectionsMenu', array('model' => $model)); ?>

<?php
$this->widget(
    'bootstrap.widgets.TbGridView',
    array(
        'type' => 'striped bordered',
        'dataProvider' => $ChildCollectionsDataProvider,
        'template' => "{items}",
        'columns' => array(
            array(
                'class' => 'CCheckBoxColumn',
                'value' => 'return null;',
                'checked' => 'return null;',
                'selectableRows' => 2,
            ),
            array(
                'class' => 'CLinkColumn',
                'labelExpression' => '$data->name',
                'urlExpression' => 'Yii::app()->urlManager->createUrl("collections/view", array("id" => $data->id))',
                'header'=>'Название',
            ),
            array(
                'header'=>'Действия',
                'htmlOptions' => array('nowrap'=>'nowrap'),
                'class'=>'bootstrap.widgets.TbButtonColumn',
                'viewButtonUrl' => 'Yii::app()->urlManager->createUrl("collections/view", array("id" => $data->id));',
                'updateButtonUrl'=> null,
                'deleteButtonUrl' => null,
                /*'buttons' => array(
                    'view' => array('visible' => 'return false;')
                )*/
            )
        ),
        'showTableOnEmpty' => false,
        'emptyText' => ''
    )
);
?>

