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
        'template' => '{items}'.PHP_EOL.'{pager}',
        'ajaxUpdate' => false,
        'columns' => array(
            array(
                'class' => 'CCheckBoxColumn',
                'value' => 'false',
                'checked' => 'false',
                'selectableRows' => 2,
                'checkBoxHtmlOptions' => array(
                    'class' => '_collectionItem',
                    'data-collection-id' => '' // айдишник будет записываться сюда джаваскриптом
                )
            ),
            array(
                'class' => 'CLinkColumn',
                'labelExpression' => '$data->name',
                'urlExpression' => 'Yii::app()->urlManager->createUrl("collections/view", array("id" => $data->id))',
                'header'=>'Название',
                'linkHtmlOptions' => array(
                    'class' => '_collectionLinkInTableRow'
                )
            ),
            array(
                'header'=>'Действия',
                'htmlOptions' => array('nowrap'=>'nowrap'),
                'class'=>'bootstrap.widgets.TbButtonColumn',
                'viewButtonUrl' => 'Yii::app()->urlManager->createUrl("collections/view", array("id" => $data->id));',
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

