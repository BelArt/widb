<?php
/* @var $this ObjectsController */
/* @var $Object Objects */
/* @var $ImagesDataProvider CActiveDataProvider */
?>

<?php $this->renderPartial('_viewImagesMenu', array('Object' => $Object)); ?>

<?php
$this->widget(
    'bootstrap.widgets.TbGridView',
    array(
        'type' => 'striped bordered',
        'dataProvider' => $ImagesDataProvider,
        'template' => "{items}",
        'columns' => array(
            array(
                'class' => 'CCheckBoxColumn',
                'value' => 'false',
                'checked' => 'false',
                'selectableRows' => 2,
                'checkBoxHtmlOptions' => array(
                    'class' => '_imageItem',
                    'data-image-id' => '' // айдишник изображения будет записываться сюда джаваскриптом
                )
            ),
            array(
                'class' => 'CLinkColumn',
                'labelExpression' => '$data->name',
                'urlExpression' => 'Yii::app()->urlManager->createUrl("images/view", array("id" => $data->id))',
                'header' => CHtml::encode(Yii::t('common', 'Размер')),
                'linkHtmlOptions' => array(
                    'class' => '_imageLinkInTableRow'
                )
            ),
            /*array(
                'value'=>'(!empty($data->author->initials) ? $data->author->initials : "")',
                'header'=>'Автор',
                'sortable' => false
            ),*/
            array(
                'header'=>'Действия',
                'htmlOptions' => array('nowrap'=>'nowrap'),
                'class'=>'bootstrap.widgets.TbButtonColumn',
                'viewButtonUrl' => 'Yii::app()->urlManager->createUrl("images/view", array("id" => $data->id));',
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