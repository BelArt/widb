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
                ),
                'headerHtmlOptions' => array(
                    'class' => 'valignedMiddle halignedCenter hidden-print',
                    'style' => 'width: 17px'
                ),
                'htmlOptions' => array(
                    'class' => 'hidden-print valignedMiddle halignedCenter'
                ),
            ),
            array(
                'class' => 'CLinkColumn',
                'labelExpression' => '$data->name',
                'urlExpression' => 'Yii::app()->urlManager->createNormalCollectionUrl($data)',
                'header'=>'Название',
                'linkHtmlOptions' => array(
                    'class' => '_collectionLinkInTableRow _normalCollectionLink'
                ),
                'htmlOptions' => array(
                    //'nowrap' => 'nowrap',
                    'class' => 'valignedMiddle'
                ),
                'headerHtmlOptions' => array(
                    'class' => 'valignedMiddle'
                ),
            ),
            array(
                'header'=>'',
                'htmlOptions' => array(
                    'nowrap' => 'nowrap',
                    'class' => 'valignedMiddle halignedCenter hidden-print actionsCell'
                ),
                'class'=>'bootstrap.widgets.TbButtonColumn',
                'viewButtonUrl' => 'Yii::app()->urlManager->createNormalCollectionUrl($data)',
                'updateButtonUrl'=> null,
                'deleteButtonUrl' => null,
                'buttons' => array(
                    'delete' => array('visible' => 'false'),
                    'view' => array(
                        'options' => array(
                            'class' => '_normalCollectionLink'
                        )
                    )
                ),
                'headerHtmlOptions' => array(
                    'class' => 'valignedMiddle halignedCenter hidden-print',
                    'style' => 'width: 35px'
                ),
            )
        ),
        'showTableOnEmpty' => false,
        'emptyText' => ''
    )
);
?>

