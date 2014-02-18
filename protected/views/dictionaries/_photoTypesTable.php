<?php
/* @var $this DictionariesController */
/* @var $PhotoTypesDataProvider CActiveDataProvider */
?>

<?php
$this->widget(
    'bootstrap.widgets.TbGridView',
    array(
        'type' => 'striped bordered',
        'dataProvider' => $PhotoTypesDataProvider,
        'template' => "{items}",
        'columns' => array(
            array(
                'name' => 'name',
                'sortable' => false
            ),
            array(
                'name' => 'description',
                'sortable' => false
            ),
            array(
                'value' => '!empty($data->sort) ? $data->sort : ""',
                'header' => Yii::t('common', 'Сортировка'),
                'sortable' => false
            ),
            array(
                'header'=>'Действия',
                'htmlOptions' => array('nowrap'=>'nowrap'),
                'class'=>'bootstrap.widgets.TbButtonColumn',
                'updateButtonUrl'=> 'Yii::app()->urlManager->createUrl("dictionaries/update", array("id" => $data->id, "type" => "photo_types"));',
                'deleteButtonUrl' => null,
                'buttons' => array(
                    'view' => array('visible' => 'false'),
                    'update' => array('visible' => Yii::app()->user->checkAccess('oDictionaryRecordEdit') ? 'true' : 'false'),
                    'delete' => array('visible' => Yii::app()->user->checkAccess('oDictionaryRecordDelete') ? 'true' : 'false')
                )
            )
        ),
        'showTableOnEmpty' => false,
        'emptyText' => ''
    )
);
?>